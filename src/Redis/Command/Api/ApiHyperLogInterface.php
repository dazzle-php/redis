<?php

namespace Dazzle\Redis\Command\Api;

interface ApiHyperLogInterface
{
    /**
     * @doc https://redis.io/commands/append
     * @since 2.0.0
     * @param $key
     * @param $value
     * @return mixed
     */
    public function append($key, $value);

    /**
     * @doc https://redis.io/commands/pfadd
     * @since 2.8.9
     * @param $key
     * @param ...$elements
     * @return mixed
     */
    public function pFAdd($key, ...$elements);

    /**
     * @doc https://redis.io/commands/pfcount
     * @since 2.8.9
     * @param ...$keys
     * @return mixed
     */
    public function pFCount(...$keys);

    /**
     * @doc https://redis.io/commands/pfmerge
     * @since 2.8.9
     * @param array $dsKeyMap
     * @return mixed
     */
    public function pFMerge(array $dsKeyMap);
}
