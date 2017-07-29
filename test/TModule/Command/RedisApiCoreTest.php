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
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_auth(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {

            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_bgRewriteAoF(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->bgRewriteAoF();
            })
            ->then(function ($value) {
                $this->assertSame('Background append only file rewriting started', $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_bgSave(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->info(['persistence']);
            })
            ->then(function ($value) use ($redis) {
                if ($value['persistence']['aof_rewrite_in_progress'] <= 0) {
                    return $redis->bgSave();
                }

                return 'An AOF log rewriting in progress';
            })
            ->then(function ($value) {
                $this->assertSame('An AOF log rewriting in progress', $value);
            });
        });
    }

    /**
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_sync(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
//                return $redis->sync();
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_time(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->time();
            })
            ->then(function ($value) {
                $this->assertNotEmpty($value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_monitor(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->monitor();
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
    public function testRedis_flushAll(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->flushAll();
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
    public function testRedis_flushDb(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->flushDb();
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
    public function testRedis_info(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->info();
            })
            ->then(function ($value) {
                $this->assertArrayHasKey('cpu', $value);
                $this->assertArrayHasKey('persistence', $value);
                $this->assertArrayHasKey('memory', $value);
                $this->assertArrayHasKey('clients', $value);
                $this->assertArrayHasKey('server', $value);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_slaveOf(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->slaveOf('127.0.0.1',6379);
            })
            ->then(function ($value) use ($redis) {
                $redis->slaveOf('no', 'one');
                $this->assertSame('OK', $value);
            });
        });
    }

    /**
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_slowLog(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.2.12', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
//                $redis->slowLog();
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_save(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [];

            return Promise::doResolve()->then(function () use ($redis, $params) {
                return $redis->save();
            })
            ->then(function ($value) {
                $this->assertSame('OK', $value);
            });
        });
    }
}