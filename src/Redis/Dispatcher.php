<?php

namespace Dazzle\Redis;

use Dazzle\Socket\Socket;
use Dazzle\Promise\Deferred;
use Dazzle\Loop\LoopInterface;
use Dazzle\Redis\Driver\Driver;
use Dazzle\Socket\SocketInterface;
use Dazzle\Event\AsyncEventEmitter;
use Dazzle\Redis\Driver\DriverInterface;
use Dazzle\Throwable\Exception\Runtime\ExecutionException;
use Dazzle\Throwable\Exception\Runtime\UnderflowException;
use Error;
use Exception;

use Clue\Redis\Protocol\Model\ErrorReply;
use Clue\Redis\Protocol\Model\ModelInterface;
use Clue\Redis\Protocol\Parser\ParserException;

class Dispatcher extends AsyncEventEmitter
{
    /**
     * Stream
     * @var SocketInterface
     */
    private $stream;

    /**
     * Deferred requests
     * @var array
     */
    private $reqs;

    /**
     * Driver of RESP protocol
     * @var DriverInterface
     */
    private $protocol;

    /**
     * Flag of ending
     * @var bool
     */
    private $ending;

    /**
     * Flag of closed
     * @var bool
     */
    private $closed;

    /**
     * Constructor
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        parent::__construct($loop);
        $this->reqs = [];
        $this->ending = false;
        $this->closed = false;
        $this->protocol = new Driver();
        $this->on('connect', [$this, 'handleConnect']);
        $this->on('response',[$this, 'handleResponse']);
        $this->on('disconnect',[$this, 'handleDisconnect']);
        $this->on('close', [$this, 'handleClose']);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->ending != true)
        {
            $this->handleDisconnect();        
        }
        else 
        {
            $this->handleClose();        
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

    /**
     * Get RESP Driver
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->protocol;
    }

    /**
     * Get dispatcher ending flag
     * @return bool
     */
    public function isEnding()
    {
        return $this->ending? true: false;
    }

    /**
     * Append request
     * @param $req
     */
    public function appendRequest($req)
    {
        $this->reqs[] = $req;
    }

    /**
     * Watch and dispatch streaming
     * @param $endpoint
     */
    public function watch($endpoint)
    {
        if ($this->stream !== null) {
            return;
        }

        try {
            $this->stream = $this->createClient($endpoint);
        } catch (\Exception $e) {
            $this->emit('error', [$e]);
        }

        if ($this->stream->isOpen()) {
            $this->emit('connect', [$this]);
        }

    }

    /**
     * @internal
     */
    public function handleConnect()
    {
        $this->stream->on('data', [$this, 'handleData']);
        $this->emit('request');
    }

    /**
     * @internal
     * @param $_
     * @param $data
     */
    public function handleData($_, $data)
    {
        try {
            $models = $this->protocol->parseResponse($data);
        } catch (ParserException $error) {
            $this->emit('error', [$error]);

            return;
        }

        foreach ($models as $data) {
            try {
                $this->emit('response', [$data]);
            } catch (UnderflowException $error) {
                $this->emit('error', [$error]);

                break;
            }
        }
    }

    /**
     * @internal
     * @param $payload
     */
    public function handleRequest($payload)
    {
        $this->stream->write($payload);
    }

    /**
     * @internal
     */
    public function handleResponse(ModelInterface $message)
    {
        if (!$this->reqs) {
            throw new UnderflowException('Unexpected reply received, no matching request found');
        }
        /* @var Deferred $req */
        $req = array_shift($this->reqs);

        if ($message instanceof ErrorReply) {
            $req->reject($message);
        } else {
            $req->resolve($message->getValueNative());
        }

        if (count($this->reqs) <= 0) {
            $this->emit('disconnect');
        }
    }

    /**
     * @internal
     */
    public function handleDisconnect()
    {
        $this->ending = true;
        // reject all remaining requests in the queue
        while($this->reqs) {
            $req = array_shift($this->reqs);
            /* @var $req Deferred */
            $req->reject(new RuntimeException('Connection closing'));
        }
        $this->stream->close();
        $this->emit('close');
    }

    /**
     * @internal
     */
    public function handleClose()
    {
        if ($this->closed) {
            return;
        }
        $this->removeListeners('connect');
        $this->removeListeners('response');
        $this->removeListeners('disconnect');
        $this->removeListeners('close');
        $this->removeListeners('error');
        $this->closed = true;
    }
}