<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\Redis;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiKeyValTest extends TModule
{
    use RedisTrait;

    /**
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetsAndGetsData(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'INPUT_VAL',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->get($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL']);
                });
        });
    }
}
