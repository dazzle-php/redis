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
    public function zAdd($key, array $options = [], array $scoreMembers = [])
    {
        $command = Enum::ZADD;
        $args = array_merge([$key], $options);
        if (!empty($scoreMembers)) {
            foreach ($scoreMembers as $score => $member) {
                $args[] = (float) $score;
                $args[] = $member;
            }
        }

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zCard($key)
    {
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
        $command = Enum::ZLEXCOUNT;
        $args = [$key, $min, $max];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRange($key, $star = 0, $stop = -1, $withScores = false)
    {
        $command = Enum::ZRANGE;
        $args = [$key, $star, $stop];
        if ($withScores) {
            $args[] = 'WITHSCORES';
            return $this->dispatch(Builder::build($command, $args))->then(function ($value) {
                $len = count($value);
                $ret = [];
                for ($i=0; $i<$len; $i+=2) {
                    $ret[$value[$i]] = $value[$i+1];
                }
                return $ret;
            });
        }

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRangeByLex($key, $min, $max, array $options = [])
    {
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
        $command = Enum::ZREM;
        $args = [$key];
        $args = array_merge($args, $members);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRemRangeByLex($key, $min, $max, array $options = [])
    {
        $command = Enum::ZREMRANGEBYLEX;
        $args = [$key, $min, $max];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRemRangeByRank($key, $start, $stop)
    {
        $command = Enum::ZREMRANGEBYRANK;
        $args = [$key, $start,$stop];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRemRangeByScore($key, $min, $max, array $options = [])
    {
        $command = Enum::ZREMRANGEBYSCORE;
        $args = [$key, $min, $max];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function zRevRange($key, $start, $stop, array $options = [])
    {
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