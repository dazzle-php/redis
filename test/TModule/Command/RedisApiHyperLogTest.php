<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiHyperLogTest extends TModule
{
    use RedisTrait;

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_pFAdd(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function (RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'E_1' => 'T_ELEMENT_1',
                'E_2' => 'T_ELEMENT_2',
                'E_3' => 'T_ELEMENT_3',
                'E_4' => 'T_ELEMENT_4',
            ];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->pFAdd($params['KEY'], $params['E_1'], $params['E_2'], $params['E_3'], $params['E_4']);
            })
            ->then(function ($value) {
                $this->assertSame(1, $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_pFCount(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function (RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'E_1' => 'T_ELEMENT_1',
                'E_2' => 'T_ELEMENT_2',
                'E_3' => 'T_ELEMENT_3',
                'E_4' => 'T_ELEMENT_4',
            ];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->pFAdd($params['KEY'], $params['E_1'], $params['E_2'], $params['E_3'], $params['E_4']);

                return $redis->pFCount($params['KEY']);
            })
            ->then(function ($value) {
                $this->assertSame(4, $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_pFMerge(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function (RedisInterface $redis) {
            $params = [
                'KEY_1' => 'T_KEY_1',
                'E_1' => 'T_ELEMENT_1',
                'E_2' => 'T_ELEMENT_2',
                'E_3' => 'T_ELEMENT_3',
                'E_4' => 'T_ELEMENT_4',
                'KEY_2' => 'T_KEY_2',
                'E_5' => 'T_ELEMENT_5',
                'E_6' => 'T_ELEMENT_6',
                'E_7' => 'T_ELEMENT_7',
                'E_8' => 'T_ELEMENT_8',
            ];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->pFAdd($params['KEY_1'], $params['E_1'], $params['E_2'], $params['E_3'], $params['E_4']);
                $redis->pFAdd($params['KEY_2'], $params['E_5'], $params['E_6'], $params['E_7'], $params['E_8']);
                $redis->pFMerge($params['KEY_1'], $params['KEY_2']);

                return $redis->pFCount($params['KEY_1']);
            })
                ->then(function ($value) {
                    $this->assertSame(8, $value);
                });
        });
    }
}