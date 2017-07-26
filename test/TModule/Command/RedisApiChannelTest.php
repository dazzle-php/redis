<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisChannelApiTest extends TModule
{
    use RedisTrait;

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_auth(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_pSubscribe(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_pubSub(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.8.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_publish(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_pUnsubscribe(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_unSubscribe(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_subscribe(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }
}