<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiCoreTrait
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
    public function bgRewriteAoF()
    {
        $command = Enum::BGREWRITEAOF;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function bgSave()
    {
        $command = Enum::BGSAVE;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sync()
    {
        $command = Enum::SYNC;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function time()
    {
        $command = Enum::TIME;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function monitor()
    {
        $command = Enum::MONITOR;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function flushAll()
    {
        $command = Enum::FLUSHALL;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function flushDb()
    {
        $command = Enum::FLUSHDB;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function info($section = [])
    {
        $command = Enum::INFO;

        return $this->dispatch(Builder::build($command, $section))->then(function ($value) {
            if ($value) {
                $ret = explode("\r\n", $value);
                $handled = [];
                $lastKey = '';

                foreach ($ret as $_ => $v)
                {
                    if (($pos = strpos($v, '#')) !== false)
                    {
                        $lastKey = strtolower(substr($v,$pos+2));
                        $handled[$lastKey] = [];
                        continue;
                    }
                    if ($v === '') {
                        continue;
                    }
                    if (($statMap = explode(':', $v)) && $statMap[0] && $statMap[1])
                    {
                        list($name, $stat) = explode(':', $v);
                        $handled[$lastKey][$name] = $stat;
                    }
                }

                return $handled;
            }

            return $value;
        });
    }

    /**
     * @override
     * @inheritDoc
     */
    public function slaveOf($host, $port)
    {
        $command = Enum::SLAVEOF;
        $args = [$host, $port];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function slowLog($subCommand, array $args = [])
    {
        $command = Enum::SLOWLOG;
        $args = array_merge([$subCommand],$args);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function save()
    {
        $command = Enum::SAVE;

        return $this->dispatch(Builder::build($command));
    }
}
