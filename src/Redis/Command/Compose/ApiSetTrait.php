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
        $command = Enum::SISMEMBER;
        $args = [$key ,$member];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sMembers($key)
    {
        $command = Enum::SMEMBERS;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sMove($src, $dst, $member)
    {
        $command = Enum::SMOVE;
        $args = [$src, $dst, $member];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sPop($key, $count)
    {
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
        $command = Enum::SUNIONSTORE;
        $args = [$dst];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }


    /**
     * @override
     * @inheritDoc
     */
    public function sAdd($key, ...$members)
    {
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
        $command = Enum::SDIFFSTORE;
        $args = [$dst];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }
}
