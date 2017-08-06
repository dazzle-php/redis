<?php

namespace Dazzle\Redis\Command\Api;

interface ApiKeyValInterface
{
    /**
     * @doc https://redis.io/commands/append
     * @since 2.0.0
     * @param $key
     * @param $value
     * @return mixed
     */
    public function append($key, $value);

    /**
     * @doc https://redis.io/commands/bitcount
     * @since 2.6.0
     * @param $key
     * @param int $start
     * @param int $end
     * @return mixed
     */
    public function bitCount($key, $start=0, $end=-1);

    /**
     * @doc https://redis.io/commands/bitfield
     * @since 3.2.0
     * @param $key
     * @param $subCommand
     * @param ...$param
     * @return mixed
     */
    public function bitField($key, $subCommand, ...$param);

    /**
     * @doc https://redis.io/commands/bitop
     * @since 2.6.0
     * @param $operation
     * @param $dstKey
     * @param $srcKey
     * @param ...$keys
     * @return mixed
     */
    public function bitOp($operation, $dstKey, $srcKey, ...$keys);

    /**
     * @doc https://redis.io/commands/bitpos
     * @since 2.8.7
     * @param $key
     * @param $bit
     * @param int $start
     * @param int $end
     * @return mixed
     */
    public function bitPos($key, $bit, $start=0, $end=-1);

    /**
     * @doc https://redis.io/commands/decr
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function decr($key);

    /**
     * @doc https://redis.io/commands/decrby
     * @since 1.0.0
     * @param $key
     * @param $decrement
     * @return mixed
     */
    public function decrBy($key, $decrement);

    /**
     * @doc https://redis.io/commands/get
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * @doc https://redis.io/commands/getbit
     * @since 2.2.0
     * @param $key
     * @param $offset
     * @return mixed
     */
    public function getBit($key, $offset);

    /**
     * @doc https://redis.io/commands/getrange
     * @since 2.4.0
     * @param $key
     * @param $start
     * @param $end
     * @return mixed
     */
    public function getRange($key, $start, $end);

    /**
     * @doc https://redis.io/commands/getset
     * @since 1.0.0
     * @param $key
     * @param $value
     * @return mixed
     */
    public function getSet($key, $value);

    /**
     * @doc https://redis.io/commands/incr
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function incr($key);

    /**
     * @doc https://redis.io/commands/incrby
     * @since 1.0.0
     * @param $key
     * @param $increment
     * @return mixed
     */
    public function incrBy($key, $increment);

    /**
     * @doc https://redis.io/commands/incrbyfloat
     * @since 2.6.0
     * @param $key
     * @param $increment
     * @return mixed
     */
    public function incrByFloat($key, $increment);

    /**
     * @doc https://redis.io/commands/set
     * @since 1.0.0
     * @param $key
     * @param $value
     * @param array $options
     * @return mixed
     */
    public function set($key, $value, array $options = []);

    /**
     * @doc https://redis.io/commands/setbit
     * @since 2.2.0
     * @param $key
     * @param $offset
     * @param $value
     * @return mixed
     */
    public function setBit($key, $offset, $value);

    /**
     * @doc https://redis.io/commands/setex
     * @since 2.0.0
     * @param $key
     * @param $seconds
     * @param $value
     * @return mixed
     */
    public function setEx($key, $seconds, $value);

    /**
     * @doc https://redis.io/commands/setnx
     * @since 1.0.0
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setNx($key, $value);

    /**
     * @doc https://redis.io/commands/setrange
     * @since 2.2.0
     * @param $key
     * @param $offset
     * @param $value
     * @return mixed
     */
    public function setRange($key, $offset, $value);

    /**
     * @doc https://redis.io/commands/psetex
     * @since 2.6.0
     * @param $key
     * @param $milliseconds
     * @param $value
     * @return mixed
     */
    public function pSetEx($key, $milliseconds, $value);

    /**
     * @doc https://redis.io/commands/mget
     * @since 1.0.0
     * @param $key
     * @param ...$values
     * @return mixed
     */
    public function mGet($key, ...$keys);

    /**
     * @doc https://redis.io/commands/mset
     * @since 1.0.1
     * @param array $kvMap
     * @return mixed
     */
    public function mSet(array $kvMap);

    /**
     * @doc https://redis.io/commands/msetnx
     * @since 1.0.1
     * @param $kvMap
     * @return mixed
     */
    public function mSetNx($kvMap);

    /**
     * @doc https://redis.io/commands/strlen
     * @since 2.2.0
     * @param $key
     * @return mixed
     */
    public function strLen($key);

    /**
     * @doc https://redis.io/commands/del
     * @since 1.0.0
     * @param $key
     * @param ...$keys
     * @return mixed
     */
    public function del($key, ...$keys);

    /**
     * @doc https://redis.io/commands/dump
     * @since 2.6.0
     * @param $key
     * @return mixed
     */
    public function dump($key);

    /**
     * @doc https://redis.io/commands/exists
     * @since 1.0.0
     * @param $key
     * @param ...$keys
     * @return mixed
     */
    public function exists($key, ...$keys);

    /**
     * @doc https://redis.io/commands/expire
     * @since 1.0.0
     * @param $key
     * @param $seconds
     * @return mixed
     */
    public function expire($key, $seconds);

    /**
     * @doc https://redis.io/commands/expireat
     * @since 1.2.0
     * @param $key
     * @param $timestamp
     * @return mixed
     */
    public function expireAt($key, $timestamp);

    /**
     * @doc https://redis.io/commands/persist
     * @since 2.2.0
     * @param $key
     * @return mixed
     */
    public function persist($key);

    /**
     * @doc https://redis.io/commands/pexpire
     * @since 2.6.0
     * @param $key
     * @param $milliseconds
     * @return mixed
     */
    public function pExpire($key, $milliseconds);

    /**
     * @doc https://redis.io/commands/pexpireat
     * @since 2.6.0
     * @param $key
     * @param $milTimestamp
     * @return mixed
     */
    public function pExpireAt($key, $milTimestamp);

    /**
     * @doc https://redis.io/commands/touch
     * @since 3.2.1
     * @param $key
     * @param ...$keys
     * @return mixed
     */
    public function touch($key, ...$keys);

    /**
     * @doc https://redis.io/commands/ttl
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function ttl($key);

    /**
     * @doc https://redis.io/commands/type
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function type($key);

    /**
     * @doc https://redis.io/commands/unlink
     * @since 4.0.0
     * @param $key
     * @param ...$keys
     * @return mixed
     */
    public function unLink($key, ...$keys);

    /**
     * @doc https://redis.io/commands/wait
     * @since 3.0.0
     * @param $numSlaves
     * @param $timeout
     * @return mixed
     */
    public function wait($numSlaves, $timeout);

    /**
     * @doc https://redis.io/commands/randomkey
     * @since 1.0.0
     * @return mixed
     */
    public function randomKey();

    /**
     * @doc https://redis.io/commands/rename
     * @since 1.0.0
     * @param $key
     * @param $newKey
     * @return mixed
     */
    public function rename($key, $newKey);

    /**
     * @doc https://redis.io/commands/renamenx
     * @since 1.0.0
     * @param $key
     * @param $newKey
     * @return mixed
     */
    public function renameNx($key, $newKey);

    /**
     * @doc https://redis.io/commands/restore
     * @since 2.6.0
     * @param $key
     * @param $ttl
     * @param $value
     * @return mixed
     */
    public function restore($key, $ttl, $value);

    /**
     * @doc https://redis.io/commands/pttl
     * @since 2.6.0
     * @param $key
     * @return mixed
     */
    public function pTtl($key);

    /**
     * @doc https://redis.io/commands/move
     * @since 1.0.0
     * @param $key
     * @param $db
     * @return mixed
     */
    public function move($key, $db);

    /**
     * @doc https://redis.io/commands/scan
     * @since 2.8.0
     * @param $cursor
     * @param array $options
     * @return mixed
     */
    public function scan($cursor, array $options = []);

    /**
     * @doc https://redis.io/commands/sort
     * @since 1.0.0
     * @param $key
     * @param array $options
     * @return mixed
     */
    public function sort($key, array $options = []);

    /**
     * @doc https://redis.io/commands/keys
     * @since 1.0.0
     * @param $key
     * @param array $options
     * @return mixed
     */
    public function keys($key = '*');
}
