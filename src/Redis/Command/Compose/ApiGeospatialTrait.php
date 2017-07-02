<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiGeospatialTrait
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
    public function geoAdd($key, array $coordinates)
    {
        // TODO: Implement geoAdd() method.
        $command = Enum::GEOADD;
        $args = [$key];
        $args = array_merge($args, $coordinates);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function geoHash($key, ...$members)
    {
        // TODO: Implement geoHash() method.
        $command = Enum::GEOHASH;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function geoPos($key, ...$members)
    {
        // TODO: Implement geoPos() method.
        $command = Enum::GEOPOS;
        $args = [$key];
        $args = array_merge($args, $members);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function geoDist($key, $memberA, $memberB, $unit)
    {
        // TODO: Implement geoDist() method.
        $command = Enum::GEODIST;
        $args = [$key, $memberA, $memberB ,$unit];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function geoRadius($key, $longitude, $latitude, $unit, $command, $count, $sort)
    {
        // TODO: Implement geoRadius() method.
        $command = Enum::GEORADIUS;
        $args = [$key, $longitude, $latitude, $unit, $command, $count, $sort];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function geoRadiusByMember($key, $member, $unit, $command, $count, $sort, $store, $storeDist)
    {
        // TODO: Implement geoRadiusByMember() method.
        $command = Enum::GEORADIUSBYMEMBER;
        $args = [$key, $member, $unit, $command, $count, $sort, $store, $storeDist];

        return $this->dispatch(Builder::build($command, $args));
    }
}
