<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiSetHashTest extends TModule
{
    use RedisTrait;

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hDel(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD' => 'T_FIELD',
                'VAL' => 'T_VAL',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hSet($params['KEY'], $params['FIELD'], $params['VAL']);

                return $redis->hDel($params['KEY'], $params['FIELD']);
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
    public function testRedis_hExists(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD' => 'T_FIELD',
                'VAL' => 'T_VAL',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->hExists($params['KEY'], $params['FIELD']);
            })
            ->then(function ($value) use ($redis, $params) {
                $this->assertSame(0, $value);
                $redis->hSet($params['KEY'], $params['FIELD'], $params['VAL']);

                return $redis->hExists($params['KEY'], $params['FIELD']);
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
    public function testRedis_hGet(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD' => 'T_FIELD',
                'VAL' => 'T_VAL',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hSet($params['KEY'], $params['FIELD'], $params['VAL']);

                return $redis->hGet($params['KEY'], $params['FIELD']);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($params['VAL'], $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hGetAll(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD_1' => 'T_FIELD_1',
                'VAL_1' => 'T_VAL_1',
                'FIELD_2' => 'T_FIELD_2',
                'VAL_2' => 'T_VAL_2',

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hMSet($params['KEY'], [
                    $params['FIELD_1'] => $params['VAL_1'],
                    $params['FIELD_2'] => $params['VAL_2'],
                ]);

                return $redis->hGetAll($params['KEY']);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame([
                    $params['FIELD_1'] => $params['VAL_1'],
                    $params['FIELD_2'] => $params['VAL_2'],
                ], $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hIncrBy(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD' => 'T_FIELD',
                'VAL' => 1,
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hSet($params['KEY'], $params['FIELD'], $params['VAL']);

                return $redis->hIncrBy($params['KEY'], $params['FIELD'], 1);
            })
            ->then(function ($value) {
                $this->assertSame(2, $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hIncrByFloat(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD' => 'T_FIELD',
                'VAL' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hSet($params['KEY'], $params['FIELD'], $params['VAL']);

                return $redis->hIncrByFloat($params['KEY'], $params['FIELD'], 1.5);
            })
                ->then(function ($value) {
                    $this->assertSame('2.5', $value);
                });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hKeys(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD_1' => 'T_FIELD_1',
                'VAL_1' => 'T_VAL_1',
                'FIELD_2' => 'T_FIELD_2',
                'VAL_2' => 'T_VAL_2',

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hMSet($params['KEY'], [
                    $params['FIELD_1'] => $params['VAL_1'],
                    $params['FIELD_2'] => $params['VAL_2'],
                ]);

                return $redis->hKeys($params['KEY']);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame([
                    $params['FIELD_1'],
                    $params['FIELD_2'],
                ], $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hLen(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD_1' => 'T_FIELD_1',
                'VAL_1' => 'T_VAL_1',
                'FIELD_2' => 'T_FIELD_2',
                'VAL_2' => 'T_VAL_2',

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hMSet($params['KEY'], [
                    $params['FIELD_1'] => $params['VAL_1'],
                    $params['FIELD_2'] => $params['VAL_2'],
                ]);

                return $redis->hLen($params['KEY']);
            })
            ->then(function ($value) {
                $this->assertSame(2, $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hMGet(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD_1' => 'T_FIELD_1',
                'VAL_1' => 'T_VAL_1',
                'FIELD_2' => 'T_FIELD_2',
                'VAL_2' => 'T_VAL_2',

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hMSet($params['KEY'], [
                    $params['FIELD_1'] => $params['VAL_1'],
                    $params['FIELD_2'] => $params['VAL_2'],
                ]);

                return $redis->hMGet($params['KEY'], $params['FIELD_1'], $params['FIELD_2']);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame([
                    $params['VAL_1'],
                    $params['VAL_2'],
                ], $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hMSet(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD_1' => 'T_FIELD_1',
                'VAL_1' => 'T_VAL_1',
                'FIELD_2' => 'T_FIELD_2',
                'VAL_2' => 'T_VAL_2',

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->hMSet($params['KEY'], [
                    $params['FIELD_1'] => $params['VAL_1'],
                    $params['FIELD_2'] => $params['VAL_2'],
                ]);
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
    public function testRedis_hSet(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD' => 'T_FIELD',
                'VAL' => 'T_VAL',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->hSet($params['KEY'], $params['FIELD'], $params['VAL']);
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
    public function testRedis_hSetNx(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD_1' => 'T_FIELD_1',
                'VAL_1' => 'T_VAL_1',
                'FIELD_2' => 'T_FIELD_2',
                'VAL_2' => 'T_VAL_2',

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hSet($params['KEY'], $params['FIELD_1'], $params['VAL_1']);

                return $redis->hSetNx($params['KEY'], $params['FIELD_1'], $params['VAL_1']);
            })
            ->then(function ($value) use ($redis, $params) {
                $this->assertSame(0, $value);

                return $redis->hSetNx($params['KEY'], $params['FIELD_2'], $params['VAL_2']);
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
    public function testRedis_hStrLen(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '3.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD' => 'T_FIELD',
                'VAL' => 'T_VAL',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hSet($params['KEY'], $params['FIELD'], $params['VAL']);

                return $redis->hStrLen($params['KEY'], $params['FIELD']);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame(strlen($params['VAL']), $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hVals(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD_1' => 'T_FIELD_1',
                'VAL_1' => 'T_VAL_1',
                'FIELD_2' => 'T_FIELD_2',
                'VAL_2' => 'T_VAL_2',

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hMSet($params['KEY'], [
                    $params['FIELD_1'] => $params['VAL_1'],
                    $params['FIELD_2'] => $params['VAL_2'],
                ]);

                return $redis->hVals($params['KEY']);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame([
                    $params['VAL_1'],
                    $params['VAL_2'],
                ], $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_hScan(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'T_KEY',
                'FIELD_1' => 'T_FIELD_1',
                'VAL_1' => 'T_VAL_1',
                'FIELD_2' => 'T_FIELD_2',
                'VAL_2' => 'T_VAL_2',

            ];
            return Promise::doResolve()->then(function () use ($redis, $params) {
                $redis->hMSet($params['KEY'], [
                    $params['FIELD_1'] => $params['VAL_1'],
                    $params['FIELD_2'] => $params['VAL_2'],
                ]);

                return $redis->hScan($params['KEY'], 0);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame([
                    '0',
                    [
                        $params['FIELD_1'],
                        $params['VAL_1'],
                        $params['FIELD_2'],
                        $params['VAL_2'],
                    ],
                ], $value);
            });
        });
    }
}