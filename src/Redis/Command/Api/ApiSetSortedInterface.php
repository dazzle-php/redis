<?php

namespace Dazzle\Redis\Command\Api;

interface ApiSetSortedInterface
{
    /**
     * @doc https://redis.io/commands/zadd
     * @since 1.2.0
     * @param $key
     * @param array $options
     * @return mixed
     */
    public function zAdd($key, array $options = []);

    /**
     * @doc https://redis.io/commands/zcard
     * @since 1.2.0
     * @param $key
     * @return mixed
     */
    public function zCard($key);

    /**
     * @doc https://redis.io/commands/zcount
     * @since 2.0.0
     * @param $key
     * @param $min
     * @param $max
     * @return mixed
     */
    public function zCount($key, $min, $max);

    /**
     * @doc https://redis.io/commands/zincrby
     * @since 1.2.0
     * @param $key
     * @param $increment
     * @param $member
     * @return mixed
     */
    public function zIncrBy($key, $increment, $member);

    /**
     * @doc https://redis.io/commands/zinterstore
     * @since 2.0.0
     * @param $dst
     * @param $numKeys
     * @return mixed
     */
    public function zInterStore($dst, $numKeys);

    /**
     * @doc https://redis.io/commands/zlexcount
     * @since 2.8.9
     * @param $key
     * @param $min
     * @param $max
     * @return mixed
     */
    public function zLexCount($key, $min, $max);

    /**
     * @doc https://redis.io/commands/zrange
     * @since 1.2.0
     * @param $key
     * @param $star
     * @param $stop
     * @param array $options
     * @return mixed
     */
    public function zRange($key, $star = 0, $stop = -1, $withScores = false);

    /**
     * @doc https://redis.io/commands/zrangebylex
     * @since 2.8.9
     * @param $key
     * @param $min
     * @param $max
     * @param array $options
     * @return mixed
     */
    public function zRangeByLex($key, $min, $max, array $options = []);

    /**
     * @doc https://redis.io/commands/zrevrangebylex
     * @since 2.8.9
     * @param $key
     * @param $max
     * @param $min
     * @param array $options
     * @return mixed
     */
    public function zRevRangeByLex($key, $max, $min, array $options = []);

    /**
     * @doc https://redis.io/commands/zrevrangebyscore
     * @since 2.2.0
     * @param $key
     * @param $min
     * @param $max
     * @param array $options
     * @return mixed
     */
    public function zRangeByScore($key, $min, $max, array $options = []);

    /**
     * @doc https://redis.io/commands/zrank
     * @since 2.0.0
     * @param $key
     * @param $member
     * @return mixed
     */
    public function zRank($key, $member);

    /**
     * @doc https://redis.io/commands/zrem
     * @since 1.2.0
     * @param $key
     * @param ...$members
     * @return mixed
     */
    public function zRem($key, ...$members);

    /**
     * @doc https://redis.io/commands/zremrangebylex
     * @since 2.8.9
     * @param $key
     * @param $min
     * @param $max
     * @return mixed
     */
    public function zRemRangeByLex($key, $min, $max);

    /**
     * @doc https://redis.io/commands/zremrangebyrank
     * @since 2.0.0
     * @param $key
     * @param $start
     * @param $stop
     * @return mixed
     */
    public function zRemRangeByRank($key, $start, $stop);

    /**
     * @doc https://redis.io/commands/zremrangebyscore
     * @since 1.2.0
     * @param $key
     * @param $min
     * @param $max
     * @return mixed
     */
    public function zRemRangeByScore($key, $min, $max);

    /**
     * @doc https://redis.io/commands/zrevrange
     * @since 1.2.0
     * @param $key
     * @param $start
     * @param $stop
     * @param array $options
     * @return mixed
     */
    public function zRevRange($key, $start, $stop, array $options = []);

    /**
     * @doc https://redis.io/commands/zrevrangebyscore
     * @since 2.2.0
     * @param $key
     * @param $max
     * @param $min
     * @param array $options
     * @return mixed
     */
    public function zRevRangeByScore($key, $max, $min, array $options = []);

    /**
     * @doc https://redis.io/commands/zrevrank
     * @since 2.0.0
     * @param $key
     * @param $member
     * @return mixed
     */
    public function zRevRank($key, $member);

    /**
     * @doc https://redis.io/commands/zscore
     * @since 1.2.0
     * @param $key
     * @param $member
     * @return mixed
     */
    public function zScore($key, $member);

    /**
     * @doc https://redis.io/commands/zunionstore
     * @since 2.0.0
     * @param $dst
     * @param $numKeys
     * @return mixed
     */
    public function zUnionScore($dst, $numKeys);

    /**
     * @doc https://redis.io/commands/zscan
     * @since 2.8.0
     * @param $key
     * @param $cursor
     * @param array $options
     * @return mixed
     */
    public function zScan($key, $cursor, array $options = []);
}
