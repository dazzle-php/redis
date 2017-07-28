<?php

namespace Dazzle\Redis\Command\Api;

interface ApiCoreInterface
{
    /**
     * @doc https://redis.io/commands/bgrewriteaof
     * @since 1.0.0
     * @return mixed
     */
    public function bgRewriteAoF();

    /**
     * @doc https://redis.io/commands/bgsave
     * @since 1.0.0
     * @return mixed
     */
    public function bgSave();

    /**
     * @doc https://redis.io/commands/sync
     * @since 1.0.0
     * @return mixed
     */
    public function sync();

    /**
     * @doc https://redis.io/commands/time
     * @since 2.6.0
     * @return mixed
     */
    public function time();

    /**
     * @doc https://redis.io/commands/monitor
     * @since 1.0.0
     * @return mixed
     */
    public function monitor();

//    public function clientList();
//    public function clientGetName();
//    public function clientPause();
//    public function clientReply($operation);
//    public function clientSetName($connetionName);

    /**
     * @doc https://redis.io/commands/flushall
     * @since 1.0.0
     * @return mixed
     */
    public function flushAll();

    /**
     * @doc https://redis.io/commands/flushdb
     * @since 1.0.0
     * @return mixed
     */
    public function flushDb();

    /**
     * @doc https://redis.io/commands/info
     * @since 1.0.0
     * @param array $section
     * @return mixed
     */
    public function info($section = []);

    /**
     * @doc https://redis.io/commands/slaveof
     * @since 1.0.0
     * @param $host
     * @param $port
     * @return mixed
     */
    public function slaveOf($host, $port);

    /**
     * @doc https://redis.io/commands/slowlog
     * @since 2.2.12
     * @param $subCommand
     * @param array $args
     * @return mixed
     */
    public function slowLog($subCommand, array $args=[]);

    /**
     * @doc https://redis.io/commands/save
     * @since 1.0.0
     * @return mixed
     */
    public function save();
}
