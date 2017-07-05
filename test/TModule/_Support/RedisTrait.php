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
use Exception;

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
        $this->checkRedisVersionedCommand($redis, '1.0.0', $scenario);
    }

    /**
     * @param RedisInterface $redis
     * @param string $minVersion
     * @param callable $scenario
     */
    public function checkRedisVersionedCommand(RedisInterface $redis, $minVersion, callable $scenario)
    {
        $this->simulate(function(SimulationInterface $sim) use($redis, $minVersion, $scenario) {
            $loop = $sim->getLoop();
            $redis->setLoop($loop);

            $redis->on('start', function(RedisInterface $redis) use($minVersion, $scenario, $sim) {
                return Promise::doResolve()
                    ->then(function() use($redis) {
                        return $redis->flushDb();
                    })
                    ->then(function() use($redis) {
                        return $redis->info();
                    })
                    ->then(function($info) use($redis, $minVersion, $scenario, $sim) {
                        if (!$info || !isset($info['server']) || !isset($info['server']['redis_version'])) {
                            throw new Exception('Test skipped due to insufficient Redis version!');
                        }
                        $current = $info['server']['redis_version'];
                        $minimal = $minVersion;
                        $cv = explode('.', $current);
                        $mv = explode('.', $minimal);
                        if (($mv[0] > $cv[0]) || ($mv[0] === $cv[0] && $mv[1] > $cv[1]) || ($mv[0] === $cv[0] && $mv[1] === $cv[1] && $mv[2] > $cv[2])) {
                            throw new Exception(
                                sprintf('Test skipped due to insufficient Redis version! Expected v%s, got v%s', $minimal, $current)
                            );
                        }
                        return Promise::doResolve($scenario($redis))
                            ->then(function() use($sim) {
                                $sim->done();
                            })
                            ->failure(function($ex) use($sim) {
                                $sim->fail((string)$ex);
                            });
                    })
                    ->failure(function($ex) use($sim) {
                        $sim->skip($ex->getMessage());
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
