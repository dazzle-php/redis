<?php

namespace Dazzle\Redis;

use Clue\Redis\Protocol\Model\ErrorReply;
use Clue\Redis\Protocol\Model\ModelInterface;
use Clue\Redis\Protocol\Parser\ParserException;
use Dazzle\Event\BaseEventEmitter;
use Dazzle\Loop\LoopAwareTrait;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\Deferred;
use Dazzle\Promise\PromiseInterface;
use Dazzle\Redis\Driver\Request;
use Dazzle\Redis\Driver\Driver;
use Dazzle\Redis\Driver\DriverInterface;
use Dazzle\Socket\Socket;
use Dazzle\Socket\SocketInterface;
use Dazzle\Throwable\Exception\Runtime\ExecutionException;
use Dazzle\Throwable\Exception\Runtime\UnderflowException;
use Error;

class Redis
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
     * @param string $endpoint
     * @param LoopInterface $loop
     */
    public function __construct($endpoint, LoopInterface $loop)
    {
        $this->endpoint = $endpoint;
        $this->loop = $loop;
        $this->dispatcher = new Dispatcher($loop);
        $this->driver = $this->dispatcher->getDriver();
    }

    /**
     *
     */
    public function __destruct()
    {

    }

    /**
     * Pipe Redis request.
     *
     * @param Request $command
     * @return PromiseInterface
     */
    private function pipe(Request $command)
    {
        $request = new Deferred();
        $promise = $request->getPromise();
        if ($this->dispatcher->isEnding())
        {
            $request->reject(new RuntimeException('Connection closed'));
        } 
        else 
        {
            $payload = $this->driver->commands($command);

            $this->dispatcher->on('request', function () use ($payload) {
                $this->dispatcher->handleRequest($payload);
            });

            $this->dispatcher->appendRequest($request);
        }

        return $promise;
    }

    public function connect($config = [])
    {
        $this->dispatcher->watch($this->endpoint);
    }
};
