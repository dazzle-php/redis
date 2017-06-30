<?php

namespace Kraken\Redis;

use Kraken\Loop\Loop;
use Kraken\Loop\LoopInterface;
use Kraken\Promise\Deferred;
use Kraken\Ipc\Socket\Socket;
use Kraken\Redis\Protocol\Resp;
use Kraken\Event\AsyncEventEmitter;
use Clue\Redis\Protocol\Model\ErrorReply;
use Clue\Redis\Protocol\Model\ModelInterface;
use RuntimeException;
use UnderflowException;
use Clue\Redis\Protocol\Parser\ParserException;

class Dispatcher extends AsyncEventEmitter
{

    /**
     * @var Socket
     */
    private $stream;
    /**
     * @var Resp
     */

    protected $requests;

    public $protocol;

    public $ending;

    public $closed;

    public function __construct(LoopInterface $loop)
    {
        parent::__construct($loop);
        $this->requests = [];
        $this->ending = false;
        $this->closed = false;
        $this->protocol = new Resp();
        $this->on('connect', [$this, 'handleConnect']);
        $this->on('response',[$this, 'handleResponse']);
        $this->on('disconnect',[$this, 'handleDisconnect']);
        $this->on('close', [$this, 'handleClose']);
    }

    public function appendRequest($request)
    {
        $this->requests[] = $request;
    }

    public function watch($uri)
    {
        if ($this->stream !== null) {
            return;
        }

        try {
            $this->stream = new Socket($uri, $this->getLoop());
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
        if (!$this->requests) {
            throw new UnderflowException('Unexpected reply received, no matching request found');
        }
        /* @var Deferred $request */
        $request = array_shift($this->requests);

        if ($message instanceof ErrorReply) {
            $request->reject($message);
        } else {
            $request->resolve($message->getValueNative());
        }

        if (count($this->requests) <= 0) {
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
        while($this->requests) {
            $request = array_shift($this->requests);
            /* @var $request Deferred */
            $request->reject(new RuntimeException('Connection closing'));
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