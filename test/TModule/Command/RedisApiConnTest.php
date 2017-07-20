<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\Redis;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiConnTest extends TModule
{
    use RedisTrait;

    /**
     * @group ignored
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
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_ping(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
             $params = [
                 'MSG' => 'MESSAGE'
             ];
             
             return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->ping($params['MSG']);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, $params['MSG']);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_quit(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
             $params = [];
             
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 return $redis->quit();
             })
             ->then(function ($value) {
                 $this->assertSame($value, 'OK');
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_select(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
             $params = [
                 'INDEX' => 0,
             ];
             
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 return $redis->select($params['INDEX']);
             })
             ->then(function ($value) {
                 $this->assertSame($value, 'OK');
             });
         });
    }

    /**
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_swapBb(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '4.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                return $redis->swapBb(0, 1);
            })
            ->then(function ($value) {
                $this->assertSame('OK', $value);
            });
        });
    }
}