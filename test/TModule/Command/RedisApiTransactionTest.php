<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiTransactionTest extends TModule
{
    use RedisTrait;

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_discard(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'U_KEY' => 'UNKNOWN_KEY'
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->multi();
                $redis->set($params['U_KEY'], 'OK');
                $redis->get($params['U_KEY']);

                return $redis->discard();
            })
            ->then(function ($value) {
                $this->assertSame('OK', $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_exec(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'VAL' => 'T_VAL',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->multi();
                $redis->set($params['KEY'], $params['VAL']);
                $redis->get($params['KEY']);

                return $redis->exec();
            })
            ->then(function ($value) use ($params) {
                $this->assertSame([
                    'OK',
                    $params['VAL'],
                ], $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_multi(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            $params = [

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->multi();
            })
            ->then(function ($value) use ($redis) {
                $this->assertSame('OK', $value);
                $redis->discard();
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_unWatch(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'T_KEY_1',
                'KEY_2' => 'T_KEY_2',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->watch($params['KEY_1'], $params['KEY_2']);

                return $redis->unWatch();
            })
            ->then(function ($value) {
                $this->assertSame('OK', $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_watch(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'T_KEY_1',
                'KEY_2' => 'T_KEY_2',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->watch($params['KEY_1'], $params['KEY_2']);
            })
            ->then(function ($value) {
                $this->assertSame('OK', $value);
            });
        });
    }
}