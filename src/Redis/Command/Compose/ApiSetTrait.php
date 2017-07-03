<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiSetTrait
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
    public function sScan($key, $cursor, array $options = [])
    {
        // TODO: Implement sScan() method.
        $command = Enum::SSCAN;
        $args = [$key, $cursor];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sInter(...$keys)
    {
        // TODO: Implement sInter() method.
        $command = Enum::SINTER;
        $args = $keys;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sInterStore($dst, ...$keys)
    {
        // TODO: Implement sInterStore() method.
        $command = Enum::SINTERSTORE;
        $args = [$dst];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sIsMember($key, $member)
    {
        // TODO: Implement sIsMember() method.
        $command = Enum::SISMEMBER;
        $args = [$key ,$member];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sLowLog($command, array $args = [])
    {
        // TODO: Implement sLowLog() method.
        $command = Enum::SLOWLOG;
        $args = array_merge([$command],$args);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sMembers($key)
    {
        // TODO: Implement sMembers() method.
        $command = Enum::SMEMBERS;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sMove($src, $dst, $members)
    {
        // TODO: Implement sMove() method.
        $command = Enum::SMOVE;
        $args = [$src, $dst];
        $args = array_merge( $args, $members);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sPop($key, $count)
    {
        // TODO: Implement sPop() method.
        $command = Enum::SPOP;
        $args = [$key, $count];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sRandMember($key, $count)
    {
        // TODO: Implement sRandMember() method.
        $command = Enum::SRANDMEMBER;
        $args = [$key, $count];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sRem($key, ...$members)
    {
        // TODO: Implement sRem() method.
        $command = Enum::SREM;
        $args = [$key];
        $args = array_merge($args, $members);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sUnion(...$keys)
    {
        // TODO: Implement sUnion() method.
        $command = Enum::SUNION;
        $args = $keys;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sUnionStore($dst, ...$keys)
    {
        // TODO: Implement sUnionStore() method.
        $command = Enum::SUNIONSTORE;
        $args = [$dst];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sWapBb($opt, $dst, ...$keys)
    {
        // TODO: Implement sWapBb() method.
        $command = Enum::SWAPDB;
        $args = [$opt, $dst];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sAdd($key, ...$members)
    {
        // TODO: Implement sAdd() method.
        $command = Enum::SADD;
        $args = [$key];
        $args = array_merge($args, $members);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sCard($key)
    {
        // TODO: Implement sCard() method.
        $command = Enum::SCARD;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sDiff(...$keys)
    {
        // TODO: Implement sDiff() method.
        $command = Enum::SDIFF;
        $args = $keys;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sDiffStore($dst, ...$keys)
    {
        // TODO: Implement sDiffStore() method.
        $command = Enum::SDIFFSTORE;
        $args = [$dst];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }
}
