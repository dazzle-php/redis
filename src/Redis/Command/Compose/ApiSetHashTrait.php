<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiSetHashTrait
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
    public function hDel($key, ...$fields)
    {
        $command = Enum::HDEL;
        $args = [$key];
        $args = array_merge($args, $fields);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hGet($key, $field)
    {
        $command = Enum::HGET;
        $args = [$key, $field];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hGetAll($key)
    {
        $command = Enum::HGETALL;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args))->then(function ($value) {
            if (!empty($value)) {
                $tmp = [];
                $size = count($value);
                for ($i=0; $i<$size; $i+=2) {
                    $field = $value[$i];
                    $val = $value[$i+1];
                    $tmp[$field] = $val;
                }
                $value = $tmp;
            }

            return $value;
        });
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hIncrBy($key, $field, $increment)
    {
        $command = Enum::HINCRBY;
        $args = [$key, $field, $increment];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hIncrByFloat($key, $field, $increment)
    {
        $command = Enum::HINCRBYFLOAT;
        $args = [$key, $field, $increment];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hKeys($key)
    {
        $command = Enum::HKEYS;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hLen($key)
    {
        $command = Enum::HLEN;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hMGet($key, ...$fields)
    {
        $command = Enum::HMGET;
        $args = [$key];
        $args = array_merge($args, $fields);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hMSet($key, array $fvMap)
    {
        $command = Enum::HMSET;
        $args = [$key];
        if (!empty($fvMap)) {
            foreach ($fvMap as $field => $value) {
                $tmp[] = $field;
                $tmp[] = $value;
            }
            $fvMap = $tmp;
        }
        $args = array_merge($args, $fvMap);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hSet($key, $field, $value)
    {
        $command = Enum::HSET;
        $args = [$key, $field, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hSetNx($key, $filed, $value)
    {
        $command = Enum::HSETNX;
        $args = [$key, $filed, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hStrLen($key, $field)
    {
        $command = Enum::HSTRLEN;
        $args = [$key, $field];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hVals($key)
    {
        $command = Enum::HVALS;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function hScan($key, $cursor, array $options = [])
    {
        // TODO: Implement hScan() method.
        $command = Enum::HSCAN;
        $args = [$key, $cursor];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @inheritDoc
     */
    public function hExists($key, $field)
    {
        $command = Enum::HEXISTS;
        $args = [$key, $field];

        return $this->dispatch(Builder::build($command, $args));
    }
}
