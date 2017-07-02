<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiChannelTrait
{
    /**
     * @param Request $request
     * @return mixed
     */
    abstract function dispatch(Request $request);

    /**
     * @override
     * @inheritDoc
     */
    public function pSubscribe(...$patterns)
    {
        // TODO: Implement pSubscribe() method.
        $command = Enum::PSUBSCRIBE;
        $args = $patterns;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pubSub($command, array $args = [])
    {
        // TODO: Implement pubSub() method.
        $command = Enum::PUBSUB;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function publish($channel, $message)
    {
        // TODO: Implement publish() method.
        $command = Enum::PUBLISH;
        $args = [$channel, $message];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pUnsubscribe(...$patterns)
    {
        // TODO: Implement pUnsubscribe() method.
        $command = Enum::PUNSUBSCRIBE;
        $args = $patterns;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function unSubscribe(...$channels)
    {
        // TODO: Implement unSubscribe() method.
        $command = Enum::UNSUBSCRIBE;
        $args = $channels;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function subscribe(...$channels)
    {
        // TODO: Implement subscribe() method.
        $command = Enum::SUBSCRIBE;
        $args = $channels;

        return $this->dispatch(Builder::build($command, $args));
    }
}