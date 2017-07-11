<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\Redis;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiListTest extends TModule
{
    use RedisTrait;

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_blPop(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
             $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2'])
                ->then(function () use ($redis, $params) {
                    return $redis->blPop([$params['LIST']], 1);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame($value, [
                        'key' => $params['LIST'],
                        'value' => $params['VAL_2']
                    ]);
                });
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_brPop(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
             $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2'])
                ->then(function () use ($redis, $params) {
                    return $redis->brPop([$params['LIST']], 1);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame($value, [
                        'key' => $params['LIST'],
                        'value' => $params['VAL_1']
                    ]);
                });
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_brPopLPush(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                 'L1' => 'INPUT_LIST_1',
                 'L2' => 'INPUT_LIST_2',
                 'L1_V1' => 'L1V1',
                 'L1_V2' => 'L1V2',
                 'L2_V1' => 'L2V1',
                 'L2_V2' => 'L2V2',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->lPush($params['L1'], $params['L1_V1'], $params['L1_V2']);
                $redis->lPush($params['L2'], $params['L2_V1'], $params['L2_V2']);
            })
            ->then(function () use ($redis, $params) {
                return $redis->brPopLpush($params['L1'], $params['L2'], 1);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, $params['L1_V1']);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lIndex(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
             $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                 return $redis->lIndex($params['LIST'], 0);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, $params['VAL_2']);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lInsert(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
             $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
                 'VAL_3' => 'V3',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                 $redis->lInsert($params['LIST'], 'before', $params['VAL_2'], $params['VAL_3']);

                 return $redis->lIndex($params['LIST'], 0);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, $params['VAL_3']);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lLen(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
             $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                 return $redis->lLen($params['LIST']);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, 2);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lPop(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                 return $redis->lPop($params['LIST'], 0);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, $params['VAL_2']);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lPush(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 return $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, 2);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lPushX(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
             $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 return $redis->lPushX($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, 0);
             })
             ->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1']);

                 return $redis->lPushX($params['LIST'], $params['VAL_2']);
             })
             ->then(function ($value) {
                 $this->assertSame($value, 2);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lRange(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
             $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                 return $redis->lRange($params['LIST'], 0, -1);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, [
                    $params['VAL_2'],
                    $params['VAL_1'],
                 ]);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lRem(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
             $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                $redis->lRem($params['LIST'], 0, $params['VAL_1']);

                return $redis->lIndex($params['LIST'], 0);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, $params['VAL_2']);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lSet(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
                 'VAL_1_RST' => 'V1_RESET',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                $redis->lSet($params['LIST'], 1, $params['VAL_1_RST']);

                return $redis->lIndex($params['LIST'], 1);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, $params['VAL_1_RST']);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_lTrim(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                $redis->lTrim($params['LIST'], 1, -1);

                return $redis->lIndex($params['LIST'], 0);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, $params['VAL_1']);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_rPop(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 $redis->lPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function () use ($redis, $params) {
                 return $redis->rPop($params['LIST'], 0);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, $params['VAL_1']);
             });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_rPopLPush(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            $params = [
                 'L1' => 'INPUT_LIST_1',
                 'L2' => 'INPUT_LIST_2',
                 'L1_V1' => 'L1V1',
                 'L1_V2' => 'L1V2',
                 'L2_V1' => 'L2V1',
                 'L2_V2' => 'L2V2',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->lPush($params['L1'], $params['L1_V1'], $params['L1_V2']);
                $redis->lPush($params['L2'], $params['L2_V1'], $params['L2_V2']);
            })
            ->then(function () use ($redis, $params) {
                return $redis->rPopLpush($params['L1'], $params['L2']);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, $params['L1_V1']);
            });
         });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_rPush(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 return $redis->rPush($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, 2);
             });
         });
    }


    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_rPushX(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                 'LIST' => 'INPUT_LIST',
                 'VAL_1' => 'V1',
                 'VAL_2' => 'V2',
             ];
             return Promise::doResolve()->then(function () use ($redis, $params) {
                 return $redis->rPushX($params['LIST'], $params['VAL_1'], $params['VAL_2']);
             })
             ->then(function ($value) use ($params) {
                 $this->assertSame($value, 0);
             })
             ->then(function () use ($redis, $params) {
                 $redis->rPush($params['LIST'], $params['VAL_1']);

                 return $redis->rPushX($params['LIST'], $params['VAL_2']);
             })
             ->then(function ($value) {
                 $this->assertSame($value, 2);
             });
         });
    }
}