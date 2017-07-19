<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiSetTest extends TModule
{
    use RedisTrait;

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sInter(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'SET' => 'T_SET',
                'E_1' => 'Hello',
                'E_2' => 'World',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->sAdd($params['SET'], $params['E_1'], $params['E_2'])
                    ->then(function ($value) {
                        $this->assertSame(2, $value);
                    });
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sInterStore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'SET_1' => 'T_SET_1',
                'S1_E1' => 'T_S1_E1',
                'SET_2' => 'T_SET_2',
                'S2_E1' => 'T_S2_E1',
                'SET_3' => 'T_SET_3',
                'INTER' => 'E_INTER',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->sAdd($params['SET_1'], $params['S1_E1'], $params['INTER']);
                $redis->sAdd($params['SET_2'], $params['S2_E1'], $params['INTER']);
            })
            ->then(function () use ($redis, $params) {
                return $redis->sInterStore($params['SET_3'], $params['SET_1'], $params['SET_2']);
            })
            ->then(function ($value) {
                $this->assertSame(1, $value);
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sIsMember(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'SET' => 'T_SET',
                'E_1' => 'T_E_1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->sIsMember($params['SET'], $params['E_1']);
            })
            ->then(function ($value) use ($redis, $params) {
                $this->assertSame(0, $value);
                $redis->sAdd($params['SET'], $params['E_1']);

                return $redis->sIsMember($params['SET'], $params['E_1']);
            })
            ->then(function ($value) {
                $this->assertSame(1, $value);
            });
        });
    }

    /**
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sLowLog(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.2.12', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                //TODO: remove this api from Set commands(wrong group)
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sMembers(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                $params = [
                    'SET' => 'T_SET',
                    'E_1' => 'T_E_1',
                ];
                return Promise::doResolve()->then(function () use ($redis, $params) {
                    return $redis->sAdd($params['SET'], $params['E_1']);
                })
                ->then(function () use ($redis, $params) {
                    return $redis->sMembers($params['SET']);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame([$params['E_1']], $value);
                });
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sMove(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                $params = [
                    'SET_1' => 'T_SET_1',
                    'S1_E1' => 'T_S1_E1',
                    'SET_2' => 'T_SET_2',
                    'S2_E1' => 'T_S2_E1',
                ];
                return Promise::doResolve()->then(function () use ($redis, $params) {
                    $redis->sAdd($params['SET_1'], $params['S1_E1'], $params['INTER']);
                    $redis->sAdd($params['SET_2'], $params['S2_E1'], $params['INTER']);
                })
                ->then(function () use ($redis, $params) {
                    return $redis->sMove($params['SET_1'], $params['SET_2'], $params['S1_E1']);
                })
                ->then(function ($value) {
                    $this->assertSame(1, $value);
                });
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sPop(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                $params = [
                    'SET' => 'T_SET',
                    'E_1' => 'T_E_1',
                ];
                return Promise::doResolve()->then(function () use ($redis, $params) {
                    return $redis->sAdd($params['SET'], $params['E_1']);
                })
                ->then(function () use ($redis, $params) {
                    return $redis->sPop($params['SET'], 1);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame([$params['E_1']], $value);
                });
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sRandMember(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                $params = [
                    'SET' => 'T_SET',
                    'E_1' => 'T_E_1',
                ];
                return Promise::doResolve()->then(function () use ($redis, $params) {
                    return $redis->sAdd($params['SET'], $params['E_1']);
                })
                ->then(function () use ($redis, $params) {
                    return $redis->sRandMember($params['SET'], 1);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame([$params['E_1']], $value);
                });
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sRem(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                $params = [
                    'SET' => 'T_SET',
                    'E_1' => 'T_E_1',
                ];
                return Promise::doResolve()->then(function () use ($redis, $params) {
                    return $redis->sAdd($params['SET'], $params['E_1']);
                })
                ->then(function () use ($redis, $params) {
                    return $redis->sRem($params['SET'], $params['E_1']);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame(1, $value);
                });
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sScan(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {

            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sUnion(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {

            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sUnionStore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {

            });
        });
    }

    /**
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sWapBb(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '4.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                //TODO: remove this api from Set commands(wrong group)
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sAdd(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {
                $params = [
                    'SET' => 'T_SET',
                    'E_1' => 'T_E_1',
                ];
                return Promise::doResolve()->then(function () use ($redis, $params) {
                    return $redis->sAdd($params['SET'], $params['E_1']);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame(1, $value);
                });
            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sCard(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {

            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sDiff(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {

            });
        });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sDiffStore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {

            });
        });
    }

}