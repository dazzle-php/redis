<?php

namespace Dazzle\Redis;

use Dazzle\Event\EventEmitterInterface;
use Dazzle\Loop\LoopAwareInterface;
use Dazzle\Redis\Command\CommandInterface;

/**
 * @event start : callable(object)
 * @event stop  : callable(object)
 * @event error : callable(object, Error|Exception)
 */
interface RedisInterface extends CommandInterface, EventEmitterInterface, LoopAwareInterface
{
    /**
     * Check if Redis client has been started.
     *
     * @return bool
     */
    public function isStarted();

    /**
     * Check if Redis client is busy with pending requests.
     *
     * @return bool
     */
    public function isBusy();

    /**
     * Start Redis client connection immediately.
     *
     * @return bool
     */
    public function start();

    /**
     * Stop Redis client connection immediately.
     *
     * @return bool
     */
    public function stop();

    /**
     * Stop Redis client connection when all closing requests has been completed.
     *
     * @return bool
     */
    public function end();
}
