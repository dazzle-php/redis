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
        // TODO: Implement blPop() method.
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
        // TODO: Implement brPop() method.
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
        // TODO: Implement brPopLPush() method.
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
        // TODO: Implement lIndex() method.
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
        // TODO: Implement lInsert() method.
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
        // TODO: Implement lLen() method.
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
        // TODO: Implement lPop() method.
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
        // TODO: Implement lRem() method.
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
        // TODO: Implement lSet() method.
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
        // TODO: Implement lTrim() method.
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
        // TODO: Implement rPopLPush() method.
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
