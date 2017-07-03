<?php

namespace Dazzle\Redis\Test\TModule\_Support;

use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\Promise;
use Dazzle\Redis\Redis;
use Dazzle\Redis\RedisInterface;
use Dazzle\Redis\Test\_Simulation\Simulation;
use Dazzle\Redis\Test\_Simulation\SimulationInterface;
use Dazzle\Redis\Test\TModule;

trait RedisTrait
{
    /**
     * Run test scenario as simulation.
     *
     * @param callable(Simulation) $scenario
     * @return TModule
     */
    abstract function simulate(callable $scenario);

    /**
     * @param RedisInterface $redis
     * @param callable $scenario
     */
    public function checkRedisCommand(RedisInterface $redis, callable $scenario)
    {
        $this->simulate(function(SimulationInterface $sim) use($redis, $scenario) {
            $loop = $sim->getLoop();
            $redis->setLoop($loop);

            $redis->on('start', function(RedisInterface $redis) use($sim, $scenario) {
                return Promise::doResolve()
                    ->then(function() use($redis) {
                        return $redis->flushDb();
                    })
                    ->then(function() use($redis, $scenario) {
                        return $scenario($redis);
                    })
                    ->then(function() use($sim) {
                        $sim->done();
                    })
                    ->failure(function($ex) use($sim) {
                        $sim->fail((string)$ex);
                    });
            });

            $sim->onStart(function() use($redis) {
                $redis->start();
            });
            $sim->onStop(function() use($redis) {
                $redis->stop();
            });
        });
    }

    /**
     * @return mixed[][]
     */
    public function redisProvider()
    {
        return [
            [ $this->createRedis() ]
        ];
    }

    /**
     * @param LoopInterface $loop
     * @return RedisInterface
     */
    public function createRedis(LoopInterface $loop = null)
    {
        $loop = $loop === null ? new Loop(new SelectLoop()) : $loop;
        return new Redis(TEST_DB_REDIS_ENDPOINT, $loop);
    }
}
