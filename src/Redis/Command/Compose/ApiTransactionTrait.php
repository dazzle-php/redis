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
        $command = Enum::DISCARD;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function exec()
    {
        $command = Enum::EXEC;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function multi()
    {
        $command = Enum::MULTI;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function unWatch()
    {
        $command = Enum::UNWATCH;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function watch($key, ...$keys)
    {
        $command = Enum::WATCH;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }
}
