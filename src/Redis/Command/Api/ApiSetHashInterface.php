<?php

namespace Dazzle\Redis\Command\Api;

interface ApiSetHashInterface
{
    /**
     * @doc https://redis.io/commands/hdel
     * @since 2.0.0
     * @param $key
     * @param ...$fields
     * @return mixed
     */
    public function hDel($key, ...$fields);

    /**
     * @doc https://redis.io/commands/hexists
     * @since 2.0.0
     * @param $key
     * @param $field
     * @return mixed
     */
    public function hExists($key, $field);

    /**
     * @doc https://redis.io/commands/hget
     * @since 2.0.0
     * @param $key
     * @param $field
     * @return mixed
     */
    public function hGet($key, $field);

    /**
     * @doc https://redis.io/commands/hgetall
     * @since 2.0.0
     * @param $key
     * @return mixed
     */
    public function hGetAll($key);

    /**
     * @doc https://redis.io/commands/hincrby
     * @since 2.0.0
     * @param $key
     * @param $field
     * @param $incrment
     * @return mixed
     */
    public function hIncrBy($key, $field, $incrment);

    /**
     * @doc https://redis.io/commands/hincrbyfloat
     * @since 2.6.0
     * @param $key
     * @param $field
     * @param $increment
     * @return mixed
     */
    public function hIncrByFloat($key, $field, $increment);

    /**
     * @doc https://redis.io/commands/hkeys
     * @since 2.0.0
     * @param $key
     * @return mixed
     */
    public function hKeys($key);

    /**
     * @doc https://redis.io/commands/hlen
     * @since 2.0.0
     * @param $key
     * @return mixed
     */
    public function hLen($key);

    /**
     * @doc https://redis.io/commands/hmget
     * @since 2.0.0
     * @param $key
     * @param ...$fields
     * @return mixed
     */
    public function hMGet($key, ...$fields);

    /**
     * @doc https://redis.io/commands/hmset
     * @since 2.0.0
     * @param $key
     * @param array $fvMap
     * @return mixed
     */
    public function hMSet($key, array $fvMap);

    /**
     * @doc https://redis.io/commands/hset
     * @since 2.0.0
     * @param $key
     * @param $field
     * @param $value
     * @return mixed
     */
    public function hSet($key, $field, $value);

    /**
     * @doc https://redis.io/commands/hsetnx
     * @since 2.0.0
     * @param $key
     * @param $filed
     * @param $value
     * @return mixed
     */
    public function hSetNx($key, $filed, $value);

    /**
     * @doc https://redis.io/commands/hstrlen
     * @since 3.2.0
     * @param $key
     * @param $field
     * @return mixed
     */
    public function hStrLen($key, $field);

    /**
     * @doc https://redis.io/commands/hvals
     * @since 2.0.0
     * @param $key
     * @return mixed
     */
    public function hVals($key);

    /**
     * @doc https://redis.io/commands/hscan
     * @since 2.8.0
     * @param $key
     * @param $cursor
     * @param array $options
     * @return mixed
     */
    public function hScan($key, $cursor, array $options = []);
}
