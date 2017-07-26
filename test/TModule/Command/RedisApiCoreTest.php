<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiCoreTest extends TModule
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
    public function testRedis_bgRewriteAoF(RedisInterface $redis)
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
    public function testRedis_bgSave(RedisInterface $redis)
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
    public function testRedis_sync(RedisInterface $redis)
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
    public function testRedis_time(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_monitor(RedisInterface $redis)
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
    public function testRedis_flushAll(RedisInterface $redis)
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
    public function testRedis_flushDb(RedisInterface $redis)
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
    public function testRedis_info(RedisInterface $redis)
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
    public function testRedis_slaveOf(RedisInterface $redis)
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
    public function testRedis_slowLog(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.2.12', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_save(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {});
        });
    }
}