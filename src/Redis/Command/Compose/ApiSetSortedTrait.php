<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiSetSortedTrait
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
    public function zAdd($key, array $options = [])
    {
        // TODO: Implement zAdd() method.
        $command = Enum::ZADD;
        $args = [$key];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zCard($key)
    {
        // TODO: Implement zCard() method.
        $command = Enum::ZCARD;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zCount($key, $min, $max)
    {
        // TODO: Implement zCount() method.
        $command = Enum::ZCOUNT;
        $args = [$key, $min, $max];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zIncrBy($key, $increment, $member)
    {
        // TODO: Implement zIncrBy() method.
        $command = Enum::ZINCRBY;
        $args = [$key, $increment, $member];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zInterStore($dst, $numKeys)
    {
        // TODO: Implement zInterStore() method.
        $command = Enum::ZINTERSTORE;
        $args = [$dst, $numKeys];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zLexCount($key, $min, $max)
    {
        // TODO: Implement zLexCount() method.
        $command = Enum::ZLEXCOUNT;
        $args = [$key, $min, $max];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRange($key, $star, $stop, array $options = [])
    {
        // TODO: Implement zRange() method.
        $command = Enum::ZRANGE;
        $args = [$key, $star,$stop];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRangeByLex($key, $min, $max, array $options = [])
    {
        // TODO: Implement zRangeByLex() method.
        $command = Enum::ZRANGEBYLEX;
        $args = [$key, $min, $max];
        $args = array_merge($args,$options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRevRangeByLex($key, $max, $min, array $options = [])
    {
        // TODO: Implement zRevRangeByLex() method.
        $command = Enum::ZREVRANGEBYLEX;
        $args = [$key, $max,$min];
        $args = array_merge($args,$options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRangeByScore($key, $min, $max, array $options = [])
    {
        // TODO: Implement zRangeByScore() method.
        $command = Enum::ZRANGEBYSCORE;
        $args = [$key, $min,$max];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRank($key, $member)
    {
        // TODO: Implement zRank() method.
        $command = Enum::ZRANK;
        $args = [$key,$member];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRem($key, ...$members)
    {
        // TODO: Implement zRem() method.
        $command = Enum::ZREM;
        $args = [$key];
        $args = array_merge($args, $members);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRemRangeByLex($key, $min, $max)
    {
        // TODO: Implement zRemRangeByLex() method.
        $command = Enum::ZREMRANGEBYLEX;
        $args = [$key, $min, $max];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRemRangeByRank($key, $start, $stop)
    {
        // TODO: Implement zRemRangeByRank() method.
        $command = Enum::ZREMRANGEBYRANK;
        $args = [$key, $start,$stop];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRemRangeByScore($key, $min, $max)
    {
        // TODO: Implement zRemRangeByScore() method.
        $command = Enum::ZREMRANGEBYSCORE;
        $args = [$key, $min, $max];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRevRange($key, $start, $stop, array $options = [])
    {
        // TODO: Implement zRevRange() method.
        $command = Enum::ZREVRANGE;
        $args = [$key, $start, $stop];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRevRangeByScore($key, $max, $min, array $options = [])
    {
        // TODO: Implement zRevRangeByScore() method.
        $command = Enum::ZREVRANGEBYSCORE;
        $args = [$key,$max,$min];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRevRank($key, $member)
    {
        // TODO: Implement zRevRank() method.
        $command = Enum::ZREVRANK;
        $args = [$key,$member];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zScore($key, $member)
    {
        // TODO: Implement zScore() method.
        $command = Enum::ZSCORE;
        $args = [$key,$member];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zScan($key, $cursor, array $options = [])
    {
        // TODO: Implement zScan() method.
        $command = Enum::ZSCAN;
        $args = [$key , $cursor];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @inheritDoc
     */
    public function zUnionScore($dst, $numKeys)
    {
        // TODO: Implement zUnionScore() method.
        $command = Enum::ZUNIIONSCORE;
        $args = [$dst, $numKeys];

        return $this->dispatch(Builder::build($command, $args));
    }
}
