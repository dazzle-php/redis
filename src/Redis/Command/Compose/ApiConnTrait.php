<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiConnTrait
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
    public function auth($password)
    {
        $command = Enum::AUTH;
        $args = [$password];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function ping($message = 'PING')
    {
        $command = Enum::PING;
        $args = [$message];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function quit()
    {
        $command = Enum::QUIT;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function select($index)
    {
        $command = Enum::SELECT;
        $args = [$index];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function swapBb($opt, $dst)
    {
        $command = Enum::SWAPDB;
        $args = [$opt, $dst];

        return $this->dispatch(Builder::build($command, $args));
    }
}
