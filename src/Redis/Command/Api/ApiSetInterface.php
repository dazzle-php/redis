<?php

namespace Dazzle\Redis\Command\Api;

interface ApiSetInterface
{
    /**
     * @doc https://redis.io/commands/sinter
     * @since 1.0.0
     * @param ...$keys
     * @return mixed
     */
    public function sInter(...$keys);

    /**
     * @doc https://redis.io/commands/sinterstore
     * @since 1.0.0
     * @param $dst
     * @param ...$keys
     * @return mixed
     */
    public function sInterStore($dst, ...$keys);

    /**
     * @doc https://redis.io/commands/sismember
     * @since 1.0.0
     * @param $key
     * @param $member
     * @return mixed
     */
    public function sIsMember($key, $member);

    /**
     * @doc https://redis.io/commands/smembers
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function sMembers($key);

    /**
     * @doc https://redis.io/commands/smove
     * @since 1.0.0
     * @param $src
     * @param $dst
     * @param $member
     * @return mixed
     */
    public function sMove($src, $dst, $member);

    /**
     * @doc https://redis.io/commands/spop
     * @since 1.0.0
     * @param $key
     * @param $count
     * @return mixed
     */
    public function sPop($key, $count);

    /**
     * @doc https://redis.io/commands/srandmember
     * @since 1.0.0
     * @param $key
     * @param $count
     * @return mixed
     */
    public function sRandMember($key, $count);

    /**
     * @doc https://redis.io/commands/srem
     * @since 1.0.0
     * @param $key
     * @param ...$members
     * @return mixed
     */
    public function sRem($key, ...$members);

    /**
     * @doc https://redis.io/commands/sscan
     * @since 2.8.0
     * @param $key
     * @param $cursor
     * @param array $options
     * @return mixed
     */
    public function sScan($key, $cursor, array $options = []);

    /**
     * @doc https://redis.io/commands/sunion
     * @since 1.0.0
     * @param ...$keys
     * @return mixed
     */
    public function sUnion(...$keys);

    /**
     * @doc https://redis.io/commands/sunionstore
     * @since 1.0.0
     * @param $dst
     * @param ...$keys
     * @return mixed
     */
    public function sUnionStore($dst, ...$keys);

    /**
     * @doc https://redis.io/commands/sadd
     * @since 1.0.0
     * @param $key
     * @param ...$members
     * @return mixed
     */
    public function sAdd($key, ...$members);

    /**
     * @doc https://redis.io/commands/scard
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function sCard($key);

    /**
     * @doc https://redis.io/commands/sdiff
     * @since 1.0.0
     * @param ...$keys
     * @return mixed
     */
    public function sDiff(...$keys);

    /**
     * @doc https://redis.io/commands/sdiffstore
     * @since 1.0.0
     * @param $dst
     * @param ...$keys
     * @return mixed
     */
    public function sDiffStore($dst, ...$keys);
}
