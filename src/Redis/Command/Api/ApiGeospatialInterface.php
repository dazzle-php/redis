<?php

namespace Dazzle\Redis\Command\Api;

interface ApiGeospatialInterface
{
    /**
     * @doc https://redis.io/commands/geoadd
     * @since 3.2.0
     * @param $key
     * @param array $coordinates
     * @return mixed
     */
    public function geoAdd($key, array $coordinates);

    /**
     * @doc https://redis.io/commands/geohash
     * @since 3.2.0
     * @param $key
     * @param ...$members
     * @return mixed
     */
    public function geoHash($key, ...$members);

    /**
     * @doc https://redis.io/commands/geopos
     * @since 3.2.0
     * @param $key
     * @param ...$members
     * @return mixed
     */
    public function geoPos($key, ...$members);

    /**
     * @doc https://redis.io/commands/geodist
     * @since 3.2.0
     * @param $key
     * @param $memberA
     * @param $memberB
     * @param $unit
     * @return mixed
     */
    public function geoDist($key, $memberA, $memberB, $unit);

    /**
     * @doc https://redis.io/commands/georadius
     * @since 3.2.0
     * @param $key
     * @param $longitude
     * @param $latitude
     * @param $unit
     * @param $command
     * @param $count
     * @param $sort
     * @return mixed
     */
    public function geoRadius($key, $longitude, $latitude, $unit, $command, $count, $sort);

    /**
     * @doc https://redis.io/commands/georadiusbymember
     * @since 3.2.0
     * @param $key
     * @param $member
     * @param $unit
     * @param $command
     * @param $count
     * @param $sort
     * @param $store
     * @param $storeDist
     * @return mixed
     */
    public function geoRadiusByMember($key, $member, $unit, $command, $count, $sort, $store, $storeDist);
}
