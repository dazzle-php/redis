<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiListTrait
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
    public function blPop(array $keys, $timeout)
    {
        $command = Enum::BLPOP;
        $keys[] = $timeout;
        $args = $keys;
        $promise = $this->dispatch(Builder::build($command, $args));
        $promise = $promise->then(function ($value) {
            if (is_array($value)) {
                list($k,$v) = $value;

                return [
                    'key'=>$k,
                    'value'=>$v
                ];
            }

            return $value;
        });

        return $promise;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function brPop(array $keys, $timeout)
    {
        $command = Enum::BRPOP;
        $keys[] = $timeout;
        $args = $keys;
        $promise = $this->dispatch(Builder::build($command, $args));
        $promise = $promise->then(function ($value) {
            if (is_array($value)) {
                list($k,$v) = $value;

                return [
                    'key'=>$k,
                    'value'=>$v
                ];
            }

            return $value;
        });

        return $promise;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function brPopLPush($src, $dst, $timeout)
    {
        $command = Enum::BRPOPLPUSH;
        $args = [$src, $dst, $timeout];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lIndex($key, $index)
    {
        $command = Enum::LINDEX;
        $args = [$key, $index];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lInsert($key, $action, $pivot, $value)
    {
        $command = Enum::LINSERT;
        $args = [$key, $action, $pivot, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lLen($key)
    {
        $command = Enum::LLEN;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lPop($key)
    {
        $command = Enum::LPOP;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lPush($key,...$values)
    {
        $command = Enum::LPUSH;
        array_unshift($values, $key);

        return $this->dispatch(Builder::build($command, $values));
    }

    public function lPushX($key, $value)
    {
        $command = Enum::LPUSHX;
        $args = [$key, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lRange($key, $start = 0, $stop = -1)
    {
        $command = Enum::LRANGE;
        $args = [$key, $start, $stop];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lRem($key, $count, $value)
    {
        $command = Enum::LREM;
        $args = [$key, $count, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lSet($key, $index, $value)
    {
        $command = Enum::LSET;
        $args = [$key, $index, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function lTrim($key, $start, $stop)
    {
        $command = Enum::LTRIM;
        $args = [$key, $start, $stop];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rPop($key)
    {
        $command = Enum::RPOP;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rPopLPush($src, $dst)
    {
        $command = Enum::RPOPLPUSH;
        $args = [$src, $dst];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rPush($key, ...$values)
    {
        $command = Enum::RPUSH;
        $args = [$key];
        $args = array_merge($args, $values);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rPushX($key, $value)
    {
        $command = Enum::RPUSHX;
        $args = [$key, $value];

        return $this->dispatch(Builder::build($command, $args));
    }
}
