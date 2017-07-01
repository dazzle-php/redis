<?php

namespace Dazzle\Redis;

use Dazzle\Redis\Command\CommandInterface;
use Dazzle\Event\EventEmitterInterface;

/**
 * @event start : callable(object)
 * @event stop  : callable(object)
 * @event error : callable(object, Error|Exception)
 */
interface RedisInterface extends EventEmitterInterface, CommandInterface
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
