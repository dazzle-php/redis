<?php

namespace Dazzle\Redis\Command\Api;

interface ApiTransactionInterface
{
    /**
     * @doc https://redis.io/commands/discard
     * @since 2.0.0
     * @return mixed
     */
    public function discard();

    /**
     * @doc https://redis.io/commands/multi
     * @since 1.2.0
     * @return mixed
     */
    public function multi();

    /**
     * @doc https://redis.io/commands/unwatch
     * @since 2.2.0
     * @return mixed
     */
    public function unWatch();

    /**
     * @doc https://redis.io/commands/watch
     * @since 2.2.0
     * @param $key
     * @param ...$keys
     * @return mixed
     */
    public function watch($key, ...$keys);
}
