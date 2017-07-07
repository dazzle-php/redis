<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\Redis;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiSetSortedTest extends TModule
{
    use RedisTrait;

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zAdd(RedisInterface $redis) 
    {
       $this->checkRedisVersionedCommand($redis, '4.0.0', function(RedisInterface $redis) {
           return Promise::doResolve()->then(function ($_) {
               return 0;
           });
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zCard(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zCount(RedisInterface $redis) 
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zIncrBy(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zInterStore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zLexCount(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRange(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRangeByLex(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRevRangeByLex(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRangeByScore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRank(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

     /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRem(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRemRangeByLex(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRemRangeByRank(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRemRangeByScore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRevRange(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRevRangeByScore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRevRank(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zScore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zUnionScore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zScan(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }
}