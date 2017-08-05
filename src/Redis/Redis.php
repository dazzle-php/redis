<?php

namespace Dazzle\Redis;

use Clue\Redis\Protocol\Model\ErrorReply;
use Clue\Redis\Protocol\Model\ModelInterface;
use Clue\Redis\Protocol\Parser\ParserException;
use Dazzle\Event\BaseEventEmitter;
use Dazzle\Loop\LoopAwareTrait;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\Deferred;
use Dazzle\Promise\Promise;
use Dazzle\Promise\PromiseInterface;
use Dazzle\Redis\Driver\Request;
use Dazzle\Redis\Driver\Driver;
use Dazzle\Redis\Driver\DriverInterface;
use Dazzle\Socket\Socket;
use Dazzle\Socket\SocketInterface;
use Dazzle\Throwable\Exception\Runtime\ExecutionException;
use Dazzle\Throwable\Exception\Runtime\UnderflowException;
use Dazzle\Throwable\Exception\Runtime\WriteException;
use Error;
use Exception;

class Redis extends BaseEventEmitter implements RedisInterface
{
    use LoopAwareTrait;
    use Command\Compose\ApiChannelTrait;
    use Command\Compose\ApiClusterTrait;
    use Command\Compose\ApiConnTrait;
    use Command\Compose\ApiCoreTrait;
    use Command\Compose\ApiGeospatialTrait;
    use Command\Compose\ApiHyperLogTrait;
    use Command\Compose\ApiKeyValTrait;
    use Command\Compose\ApiListTrait;
    use Command\Compose\ApiSetTrait;
    use Command\Compose\ApiSetHashTrait;
    use Command\Compose\ApiSetSortedTrait;
    use Command\Compose\ApiTransactionTrait;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var SocketInterface
     */
    protected $stream;

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var bool
     */
    protected $isConnected;

    /**
     * @var bool
     */
    protected $isBeingDisconnected;

    /**
     * @var PromiseInterface|null;
     */
    protected $endPromise;

    /**
     * @var array
     */
    private $reqs;

    /**
     * @param string $endpoint
     * @param LoopInterface $loop
     */
    public function __construct($endpoint, LoopInterface $loop)
    {
        $this->endpoint = $endpoint;
        $this->loop = $loop;
        $this->stream = null;
        $this->driver = new Driver();

        $this->isConnected = false;
        $this->isBeingDisconnected = false;
        $this->endPromise = null;

        $this->reqs = [];
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->stop();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function isPaused()
    {
        return $this->stream === null ? false : $this->stream->isPaused();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pause()
    {
        if ($this->stream !== null)
        {
            $this->stream->pause();
        }
    }

    /**
     * @override
     * @inheritDoc
     */
    public function resume()
    {
        if ($this->stream !== null)
        {
            $this->stream->resume();
        }
    }

    /**
     * @override
     * @inheritDoc
     */
    public function isStarted()
    {
        return $this->isConnected;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function isBusy()
    {
        return !empty($this->reqs);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function start()
    {
        if ($this->isStarted())
        {
            return Promise::doResolve($this);
        }

        $ex = null;
        $stream = null;

        try
        {
            $stream = $this->createClient($this->endpoint);
        }
        catch (Error $ex)
        {}
        catch (Exception $ex)
        {}

        if ($ex !== null)
        {
            return Promise::doReject($ex);
        }

        $this->isConnected = true;
        $this->isBeingDisconnected = false;
        $this->stream = $stream;
        $this->handleStart();
        $this->emit('start', [ $this ]);

        return Promise::doResolve($this);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function stop()
    {
        if (!$this->isStarted())
        {
            return Promise::doResolve($this);
        }

        $this->isBeingDisconnected = true;
        $this->isConnected = false;

        $this->stream->close();
        $this->stream = null;

        foreach ($this->reqs as $req)
        {
            $req->reject(new ExecutionException('Connection has been closed!'));
        }

        $this->reqs = [];
        $this->handleStop();
        $this->emit('stop', [ $this ]);

        if ($this->endPromise !== null)
        {
            $promise = $this->endPromise;
            $this->endPromise = null;
            $promise->resolve($this);
        }

        return Promise::doResolve($this);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function end()
    {
        if (!$this->isStarted())
        {
            return Promise::doResolve($this);
        }
        if ($this->isBeingDisconnected)
        {
            return Promise::doReject(new WriteException('Tried to double end same connection.'));
        }

        $promise = new Promise();
        $this->isBeingDisconnected = true;
        $this->endPromise = $promise;

        return $promise;
    }

    /**
     * Dispatch Redis request.
     *
     * @param Request $command
     * @return PromiseInterface
     */
    protected function dispatch(Request $command)
    {
        $request = new Deferred();
        $promise = $request->getPromise();

        if ($this->isBeingDisconnected)
        {
            $request->reject(new ExecutionException('Redis client connection is being stopped now.'));
        }
        else
        {
            $this->stream->write($this->driver->commands($command));
            $this->reqs[] = $request;
        }

        return $promise;
    }

    /**
     * @internal
     */
    protected function handleStart()
    {
        if ($this->stream !== null)
        {
            $this->stream->on('data', [ $this, 'handleData' ]);
            $this->stream->on('close', [ $this, 'stop' ]);
        }
    }

    /**
     * @internal
     */
    protected function handleStop()
    {
        if ($this->stream !== null)
        {
            $this->stream->removeListener('data', [ $this, 'handleData' ]);
            $this->stream->removeListener('close', [ $this, 'stop' ]);
        }
    }

    /**
     * @internal
     * @param SocketInterface $stream
     * @param string $chunk
     */
    public function handleData($stream, $chunk)
    {
        try
        {
            $models = $this->driver->parseResponse($chunk);
        }
        catch (ParserException $error)
        {
            $this->emit('error', [ $this, $error ]);
            $this->stop();
            return;
        }

        foreach ($models as $data)
        {
            try
            {
                $this->handleMessage($data);
            }
            catch (UnderflowException $error)
            {
                $this->emit('error', [ $this, $error ]);
                $this->stop();
                return;
            }
        }
    }

    /**
     * @internal
     * @param ModelInterface $message
     */
    protected function handleMessage(ModelInterface $message)
    {
        if (!$this->reqs)
        {
            throw new UnderflowException('Unexpected reply received, no matching request found');
        }

        $request = array_shift($this->reqs);

        if ($message instanceof ErrorReply)
        {
            $request->reject($message);
        }
        else
        {
            $request->resolve($message->getValueNative());
        }

        if ($this->isBeingDisconnected && !$this->isBusy())
        {
            $this->stop();
        }
    }

    /**
     * Create socket client with connection to Redis database.
     *
     * @param string $endpoint
     * @return SocketInterface
     * @throws ExecutionException
     */
    protected function createClient($endpoint)
    {
        $ex = null;

        try
        {
            return new Socket($endpoint, $this->loop);
        }
        catch (Error $ex)
        {}
        catch (Exception $ex)
        {}

        throw new ExecutionException('Redis connection socket could not be created!', 0, $ex);
    }
};
