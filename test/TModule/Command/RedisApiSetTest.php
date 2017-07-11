<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\Redis;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiSetTest extends TModule
{
    use RedisTrait;

    /**
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_(RedisInterface $redis)
    {
       
    }
}