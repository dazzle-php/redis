<?php

namespace Dazzle\Redis\Test\_Simulation;

use Dazzle\Event\EventEmitterInterface;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\PromiseInterface;

interface SimulationInterface extends EventEmitterInterface
{
    /**
     * @param string $key
     * @param mixed $val
     */
    public function setParam($key, $val);

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getParam($key);

    /**
     * @return LoopInterface
     */
    public function getLoop();

    /**
     * @return int
     */
    public function getState();

    /**
     * @return string
     */
    public function getStateMessage();

    /**
     *
     */
    public function done();

    /**
     *
     */
    public function skip($message);

    /**
     * @param string $message
     */
    public function fail($message);

    /**
     * @param mixed $expected
     * @param mixed $actual
     * @param string $message
     */
    public function assertSame($expected, $actual, $message = 'Assertion failed');

    /**
     * @param string $name
     * @param mixed $data
     */
    public function expect($name, $data = []);

    /**
     * @param callable $callable
     */
    public function onStart(callable $callable);

    /**
     * @param callable $callable
     */
    public function onStop(callable $callable);

    /**
     * @param string $model
     * @param mixed[] $config
     * @return object
     */
    public function reflect($model, $config = []);
}
