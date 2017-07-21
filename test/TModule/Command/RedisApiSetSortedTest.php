<?php

namespace Dazzle\Redis\Test\TModule\Command;

use Dazzle\Promise\Promise;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\TModule;
use Dazzle\Redis\Test\TModule\_Support\RedisTrait;

class RedisApiSetSortedTest extends TModule
{
    use RedisTrait;

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zAdd(RedisInterface $redis) 
    {
       $this->checkRedisVersionedCommand($redis, '4.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'SCORE_1' => 1,
                'SCORE_2' => 2,
            ];
           return Promise::doResolve()->then(function ($_) use ($redis, $params){
               return $redis->zAdd($params['KEY'],[],
                   $params['SCORE_1'],
                   $params['MEB_1'],
                   $params['SCORE_2'],
                   $params['MEB_2']
               );
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
    public function testRedis_zCard(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'SCORE_1' => 1,
                'SCORE_2' => 2,
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'],[],
                    $params['SCORE_1'], 
                    $params['MEB_1'],
                    $params['SCORE_2'], 
                    $params['MEB_2']
                );
            })
            ->then(function() use ($redis, $params){
                return $redis->zCard($params['KEY']);
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
    public function testRedis_zCount(RedisInterface $redis) 
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'SCORE_1' => 1,
                'SCORE_2' => 2,
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'],[],
                    $params['SCORE_1'],
                    $params['MEB_1'],
                    $params['SCORE_2'],
                    $params['MEB_2']
                );
            })
            ->then(function() use ($redis, $params){
                return $redis->zCount($params['KEY'], 0, '+inf');
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
    public function testRedis_zIncrBy(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'SCORE_1' => 1,
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'],[],
                        $params['SCORE_1'],
                        $params['MEB_1']
                    );
                })
                ->then(function() use ($redis, $params){
                    return $redis->zIncrBy($params['KEY'], 1, $params['MEB_1']);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame((int)$value, $params['SCORE_1'] + 1);
                });
            });
    }

    /**
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zInterStore(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve()->then(function () use ($redis) {

            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zLexCount(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
               );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zLexCount($params['KEY'], '[A', '[F');
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, 6);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRange(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
           $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'SCORE_1' => '1',
                'SCORE_2' => '2',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'],[],
                    $params['SCORE_1'],
                    $params['MEB_1'],
                    $params['SCORE_2'],
                    $params['MEB_2']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRange($params['KEY'], 0, -1, true);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, [
                    $params['MEB_1'] => $params['SCORE_1'],
                    $params['MEB_2'] => $params['SCORE_2'],
                ]);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRangeByLex(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRangeByLex($params['KEY'], '[A', '[F');
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, [
                    $params['MEB_1'],
                    $params['MEB_2'],
                    $params['MEB_3'],
                    $params['MEB_4'],
                    $params['MEB_5'],
                    $params['MEB_6'],
                ]);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRevRangeByLex(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRevRangeByLex($params['KEY'], '[F', '[A');
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, [
                    $params['MEB_6'],
                    $params['MEB_5'],                    
                    $params['MEB_4'],
                    $params['MEB_3'],
                    $params['MEB_2'],
                    $params['MEB_1'],
                ]);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRangeByScore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRangeByScore($params['KEY'], 0, 1, true, 0, 1);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, [
                    $params['MEB_1'] => $params['SCORE'],
                ]);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRank(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'SCORE_1' => 1,
                'SCORE_2' => 2,
            ];
           return Promise::doResolve()->then(function ($_) use ($redis, $params){
               return $redis->zAdd($params['KEY'],[],
                   $params['SCORE_1'],
                   $params['MEB_1'],
                   $params['SCORE_2'],
                   $params['MEB_2']
               );
           })
           ->then(function () use ($redis, $params) {
               return $redis->zRank($params['KEY'], $params['MEB_2']);
           })
           ->then(function ($value) {
               $this->assertSame($value, 1);
           });
       });
    }

     /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRem(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRem($params['KEY'], 
                    $params['MEB_1'], 
                    $params['MEB_2'], 
                    $params['MEB_3'],
                    $params['MEB_4'],
                    $params['MEB_5']
                );
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, 5);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRemRangeByLex(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.8.9', function(RedisInterface $redis) {
             $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRemRangeByLex($params['KEY'], '[A', '(F');
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, 5);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRemRangeByRank(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
             $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRemRangeByRank($params['KEY'], 0, 1);
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
    public function testRedis_zRemRangeByScore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
             $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRemRangeByScore($params['KEY'], 1, 1);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, 6);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRevRange(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
              $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRevRange($params['KEY'], 0, -1, true);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, [
                    $params['MEB_6'] => $params['SCORE'],
                    $params['MEB_5'] => $params['SCORE'],                    
                    $params['MEB_4'] => $params['SCORE'],
                    $params['MEB_3'] => $params['SCORE'],
                    $params['MEB_2'] => $params['SCORE'],
                    $params['MEB_1'] => $params['SCORE'],
                ]);
            });
       });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRevRangeByScore(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
             $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRevRangeByScore($params['KEY'], 1, 0, true, 0, 1);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, [
                    $params['MEB_6'] => $params['SCORE'],
                ]);
            });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zRevRank(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
             $params = [
                'KEY' => "K",
                'MEB_1' => 'A',
                'MEB_2' => 'B',
                'MEB_3' => 'C',
                'MEB_4' => 'D',
                'MEB_5' => 'E',
                'MEB_6' => 'F',
                'SCORE' => '1',
            ];
            return Promise::doResolve()->then(function () use ($redis, $params){
                return $redis->zAdd($params['KEY'], [],
                    $params['SCORE'],
                    $params['MEB_1'],
                    $params['SCORE'],
                    $params['MEB_2'],
                    $params['SCORE'],
                    $params['MEB_3'],
                    $params['SCORE'],
                    $params['MEB_4'],
                    $params['SCORE'],
                    $params['MEB_5'],
                    $params['SCORE'],
                    $params['MEB_6']
                );
            })
            ->then(function() use ($redis, $params) {
                return $redis->zRevRank($params['KEY'], $params['MEB_1']);
            })
            ->then(function ($value) use ($params) {
                $this->assertSame($value, 5);
            });
       });
    }

    /**
     * @group testing
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_zScore(RedisInterface $redis)
    {
        //TODO: Impementations
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
        //TODO: Impementations        
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            return Promise::doResolve();
       });
    }

    /**
     * @group ignored
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