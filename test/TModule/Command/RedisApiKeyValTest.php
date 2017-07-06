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
     * @group passed
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
                ->then(function($value) use($params, $redis) {
                    $this->assertSame($value, 'OK');
                    
                    return $redis->get($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL']);
                });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetsAndAppendsData(RedisInterface $redis)
    {
         $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'INPUT_VAL',
                'APPEND' => 'APPEND VAL',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->append($params['KEY'],$params['APPEND']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, strlen($params['VAL'].$params['APPEND']));
                });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetsBitAndGetsBitCount(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => "foobar",
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->bitCount($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 26);
                });
        }); 
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetBitAndBitOp(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'INPUT_KEY_1',
                'VAL_1' => 1,
                'KEY_2' => 'INPUT_KEY_2',
                'VAL_2' => 0,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    $redis->setBit($params['KEY_1'], 0, $params['VAL_1']);
                    $redis->setBit($params['KEY_2'], 0, $params['VAL_2']);
                })
                ->then(function() use($params, $redis) {                    
                    $redis->bitOp('AND', $params['KEY_1'], $params['KEY_2']);

                    return $redis->getBit($params['KEY_1'], 0);
                })
                ->then(function($value) use($params, $redis) {
                    $this->assertSame($value, 0);
                    $redis->bitOp('OR', $params['KEY_1'], $params['KEY_2']);
                
                    return $redis->getBit($params['KEY_1'], 0);
                })
                ->then(function ($value) use ($params, $redis) {
                    $this->assertSame($value, 0);
                    $redis->bitOp('XOR', $params['KEY_1'], $params['KEY_2']);

                    return $redis->getBit($params['KEY_1'], 0);
                })
                ->then(function ($value) use ($params, $redis) {
                    $this->assertSame($value, 0);
                    $redis->bitOp('NOT', $params['KEY_1'], $params['KEY_1']);

                    return $redis->getBit($params['KEY_1'], 0);
                })
                ->then(function ($value) use ($params) {
                    $this->assertSame($value, 1);
                });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetDataAndGetBitPos(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.8.7', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => "\xff\xf0\x00",
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->bitPos($params['KEY'], 0);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 12);
                });
        }); 
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndDecrData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->decr($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL'] - 1);
                });
        }); 
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndDecrDataByNum(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
                'DECR' => 3,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->decrBy($params['KEY'], $params['DECR']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL'] - $params['DECR']);
                });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndGetRange(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.4.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'This is a string',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->getRange($params['KEY'], 0, 3);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, substr($params['VAL'], 0, 4));
                });
        }); 
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndGetSet(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'INPUT_VAL',
                'VAL_2' => 'INPUT_VAL_2',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->getSet($params['KEY'], $params['VAL_2']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL']);
                });
        }); 
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetBitAndGetBit(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->setBit($params['KEY'], 0, 1);
                })
                ->then(function() use($params, $redis) {
                    return $redis->getBit($params['KEY'], 0);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL']);
                });
        }); 
    }

    /**
     * @group ignored
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetBitFiledAndGetBit(RedisInterface $redis)
    {
        //TODO: Implementation
       $this->checkRedisVersionedCommand($redis, '3.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                })
                ->then(function() use($params, $redis) {
                })
                ->then(function($value) use($params) {
                });
        });
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndIncrData(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->incr($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL'] + 1);
                });
        }); 
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndIncrDataByNum(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->incrBy($params['KEY'], 2);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL'] + 2);
                });
        }); 
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndIncrByFloat(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->incrByFloat($params['KEY'], 1.5);
                })
                ->then(function($value) use($params) {
                    $this->assertSame((float)$value, $params['VAL'] + 1.5);
                });
        }); 
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetExAndSetNx(RedisInterface $redis)
    {
        $this->checkRedisVersionedCommand($redis, '2.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'INPUT_VALUE',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->setEx($params['KEY'], 1, $params['VAL']);
                })
                ->then(function($value) use($params, $redis) {
                    $this->assertSame($value, 'OK');
                    sleep(1);
                    return $redis->setNx($params['KEY'], $params['VAL']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 1);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetRange(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'Hello World',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    $redis->setRange($params['KEY'], 6, 'Redis');
                    return $redis->get($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 'Hello Redis');
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_pSetExData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    $val = $redis->pSetEx($params['KEY'], 1000 * 1000 * 60, $params['VAL']);

                    return $val;
                })->then(function () use ($params, $redis) {
                    
                    return $redis->pttl($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertTrue($value > 1);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_mGetData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'INPUT_KEY_1',
                'VAL_1' => 'INPUT_VAL_1',
                'KEY_2' => 'INPUT_KEY_2',
                'VAL_2' => 'INPUT_VAL_2',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    $redis->set($params['KEY_1'], $params['VAL_1']);
                    $redis->set($params['KEY_2'], $params['VAL_2']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->mGet($params['KEY_1'], $params['KEY_2']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, [
                        $params['VAL_1'],
                        $params['VAL_2']
                    ]);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_mSetData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.1', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'INPUT_KEY_1',
                'VAL_1' => 'INPUT_VAL_1',
                'KEY_2' => 'INPUT_KEY_2',
                'VAL_2' => 'INPUT_VAL_2',  
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->mSet([
                        $params['KEY_1'] => $params['VAL_1'],
                        $params['KEY_2'] => $params['VAL_2'],
                    ]);
                })
                ->then(function() use($params, $redis) {
                    return $redis->mGet($params['KEY_1'], $params['KEY_2']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, [
                        $params['VAL_1'],
                        $params['VAL_2'],
                    ]);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_mSetNxData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.1', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'INPUT_KEY_1',
                'VAL_1' => 'INPUT_VAL_1',
                'KEY_2' => 'INPUT_KEY_2',
                'VAL_2' => 'INPUT_VAL_2',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->mSetNx([
                        $params['KEY_1'] => $params['VAL_1'],
                        $params['KEY_2'] => $params['VAL_2'],
                    ]);
                })
                ->then(function() use($params, $redis) {
                    return $redis->mGet($params['KEY_1'], $params['KEY_2']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, [
                        $params['VAL_1'],
                        $params['VAL_2'],
                    ]);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_GetStringLength(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'INPUT_VAL',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->strLen($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, strlen($params['VAL']));
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndDelData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->del($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 1);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndDumpData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->dump($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertNotNull($value);
                });
        });
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetDataAndCountExists(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'INPUT_KEY_1',
                'VAL_1' => 1,
                'KEY_2' => 'INPUT_KEY_2',
                'VAL_2' => 2,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->mSet([
                        $params['KEY_1'] => $params['VAL_1'],
                        $params['KEY_2'] => $params['VAL_2'],
                    ]);
                })
                ->then(function() use($params, $redis) {
                    return $redis->exists($params['KEY_1'], $params['KEY_2']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 2);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndExpireData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->setEx($params['KEY'], 60, $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->ttl($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertTrue($value > 1);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndExpireDataAtTime(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    $redis->set($params['KEY'], $params['VAL']);
                    $redis->expireAt($params['KEY'], time() + 60);
                })
                ->then(function() use($params, $redis) {
                    return $redis->ttl($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertTrue($value > 1);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetDataExpireAndPersist(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.2.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->setEx($params['KEY'], 60, $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->persist($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 1);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndPExpireData(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    $redis->pExpire($params['KEY'], 60 * 1000 * 1000 );

                    return $redis->pTtl($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertTrue($value > 1);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndPExpireDataAtTime(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    $redis->pExpireAt($params['KEY'], (time() + 60) * 1000 );

                    return $redis->pTtl($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertTrue($value > 1);
                });
        });  
    }

    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetStringAndGetDataType(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'string',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->type($params['KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL']);
                });
        }); 
    }

      /**
     * @group ignore
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_Wait(RedisInterface $redis)
    {
        //TODO: Implementation
       $this->checkRedisVersionedCommand($redis, '3.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    // $redis->set($params['KEY'], $params['VAL']);

                    // return $redis->wait(1, 0);
                })
                ->then(function($value) use($params) {
                    // $this->assertSame($value, 1);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetDataAndGetRandomKey(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->randomKey();
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['KEY']);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetDataAndRenameKey(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'NEW_KEY' => 'NEW_INPUT_KEY',
                'VAL' => 'INPUT_VAL',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    $redis->rename($params['KEY'], $params['NEW_KEY']);
                    
                    return $redis->get($params['NEW_KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL']);
                });
        }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetDataAndRanameKeyNx(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
                'NEW_KEY' => 'INPUT_NEW_KEY',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    return $redis->set($params['KEY'], $params['VAL']);
                })
                ->then(function() use($params, $redis) {
                    return $redis->renameNx($params['KEY'], $params['NEW_KEY']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, $params['VAL']);
                });
            }); 
    }

      /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_Restore(RedisInterface $redis)
    {
       $this->checkRedisVersionedCommand($redis, '2.6.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 'INPUT_VAL',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    $redis->set($params['KEY'], $params['VAL']);

                    return $redis->dump($params['KEY']);
                })
                ->then(function($value) use($params, $redis) {
                    $redis->del($params['KEY']);

                    return $redis->restore($params['KEY'], 0, $value);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 'OK');
                });
        }); 
    }

    /**
     * @group ignore
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_Move(RedisInterface $redis)
    {
        //TODO: Implementation
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                })
                ->then(function() use($params, $redis) {
                })
                ->then(function($value) use($params) {
                });
        }); 
    }

      /**
     * @group ignore
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_Scan(RedisInterface $redis)
    {
        //TODO: Implementation
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                })
                ->then(function() use($params, $redis) {
                })
                ->then(function($value) use($params) {
                });
        }); 
    }
      /**
     * @group ignore
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_Sort(RedisInterface $redis)
    {
        //TODO: Implementation
       $this->checkRedisVersionedCommand($redis, '1.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY' => 'INPUT_KEY',
                'VAL' => 1,
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                })
                ->then(function() use($params, $redis) {
                })
                ->then(function($value) use($params) {
                });
        }); 
    }
    
    /**
     * @group passed
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndTouchData(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '3.2.1', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'INPUT_KEY_1',
                'KEY_2' => 'INPUT_KEY_2',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    $redis->set($params['KEY_1'], 0);
                    $redis->set($params['KEY_2'], 0);

                    return 0;
                })
                ->then(function() use($params, $redis) {
                    return $redis->touch($params['KEY_1'], $params['KEY_2']);
                })
                ->then(function($value) use($params) {
                    $this->assertSame($value, 2);
                });
        });
    }

    /**
     * @group ignore
     * @dataProvider redisProvider
     * @param RedisInterface $redis
     */
    public function testRedis_SetAndUnLinkData(RedisInterface $redis)
    {
        //TODO: Implementation
        $this->checkRedisVersionedCommand($redis, '4.0.0', function(RedisInterface $redis) {
            $params = [
                'KEY_1' => 'INPUT_KEY_1',
                'KEY_2' => 'INPUT_KEY_2',
            ];
            return Promise::doResolve()
                ->then(function() use($params, $redis) {
                    //    
                })
                ->then(function() use($params, $redis) {
                    // 
                })
                ->then(function($value) use($params) {
                    // 
                });
        });
    }
}