<?php

namespace Dazzle\Redis\Command\Api;

interface ApiListInterface
{
    /**
     * @doc https://redis.io/commands/blpop
     * @since 2.0.0
     * @param array $keys
     * @param $timeout
     * @return mixed
     */
    public function blPop(array $keys, $timeout);

    /**
     * @doc https://redis.io/commands/brpop
     * @since 2.0.0
     * @param array $keys
     * @param $timeout
     * @return mixed
     */
    public function brPop(array $keys, $timeout);

    /**
     * @doc https://redis.io/commands/brpoplpush
     * @since 2.2.0
     * @param $src
     * @param $dst
     * @param $timeout
     * @return mixed
     */
    public function brPopLPush($src, $dst, $timeout);

    /**
     * @doc https://redis.io/commands/lindex
     * @since 1.0.0
     * @param $key
     * @param $index
     * @return mixed
     */
    public function lIndex($key, $index);

    /**
     * @doc https://redis.io/commands/linsert
     * @since 2.2.0
     * @param $key
     * @param $action
     * @param $pivot
     * @param $value
     * @return mixed
     */
    public function lInsert($key, $action, $pivot, $value);

    /**
     * @doc https://redis.io/commands/llen
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function lLen($key);

    /**
     * @doc https://redis.io/commands/lpop
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function lPop($key);

    /**
     * @doc https://redis.io/commands/lpush
     * @since 1.0.0
     * @param string $key
     * @param ...$values
     * @return mixed
     */
    public function lPush($key, ...$values);

    /**
     * @doc https://redis.io/commands/lpushx
     * @since 2.2.0
     * @param $key
     * @param $value
     * @return mixed
     */
    public function lPushX($key, $value);

    /**
     * @doc https://redis.io/commands/lrange
     * @since 1.0.0
     * @param $key
     * @param $start
     * @param $stop
     * @return mixed
     */
    public function lRange($key, $start, $stop);

    /**
     * @doc https://redis.io/commands/lrem
     * @since 1.0.0
     * @param $key
     * @param $count
     * @param $value
     * @return mixed
     */
    public function lRem($key, $count, $value);

    /**
     * @doc https://redis.io/commands/lset
     * @since 1.0.0
     * @param $key
     * @param $index
     * @param $value
     * @return mixed
     */
    public function lSet($key, $index, $value);

    /**
     * @doc https://redis.io/commands/ltrim
     * @since 1.0.0
     * @param $key
     * @param $start
     * @param $stop
     * @return mixed
     */
    public function lTrim($key, $start, $stop);

    /**
     * @doc https://redis.io/commands/rpop
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function rPop($key);

    /**
     * @doc https://redis.io/commands/rpoplpush
     * @since 1.2.0
     * @param $src
     * @param $dst
     * @return mixed
     */
    public function rPopLPush($src, $dst);

    /**
     * @doc https://redis.io/commands/rpush
     * @since 1.0.0
     * @param $key
     * @param ...$values
     * @return mixed
     */
    public function rPush($key, ...$values);


    /**
     * @doc https://redis.io/commands/rpushx
     * @since 2.2.0
     * @param $key
     * @param $value
     * @return mixed
     */
    public function rPushX($key, $value);
}
