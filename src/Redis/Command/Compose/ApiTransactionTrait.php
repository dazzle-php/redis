<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiTransactionTrait
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
    public function discard()
    {
        // TODO: Implement discard() method.
        $command = Enum::DISCARD;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function multi()
    {
        // TODO: Implement multi() method.
        $command = Enum::MULTI;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function unWatch()
    {
        // TODO: Implement unWatch() method.
        $command = Enum::UNWATCH;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function watch($key, ...$keys)
    {
        // TODO: Implement watch() method.
        $command = Enum::WATCH;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }
}
