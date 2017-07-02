<?php

namespace Dazzle\Redis\Command\Api;

interface ApiConnInterface
{
    /**
     * @doc https://redis.io/commands/auth
     * @since 1.0.0
     * @param $password
     * @return mixed
     */
    public function auth($password);

    /**
     * @doc https://redis.io/commands/ping
     * @since 1.0.0
     * @param string $message
     * @return mixed
     */
    public function ping($message='pong');

    /**
     * @doc https://redis.io/commands/quit
     * @since 1.0.0
     * @return mixed
     */
    public function quit();

    /**
     * @doc https://redis.io/commands/select
     * @since 1.0.0
     * @param $index
     * @return mixed
     */
    public function select($index);
}
