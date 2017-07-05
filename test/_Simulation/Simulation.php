<?php

namespace Dazzle\Redis\Test\_Simulation;

use Dazzle\Event\BaseEventEmitter;
use Dazzle\Loop\LoopExtendedInterface;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\PromiseInterface;
use Exception;
use ReflectionClass;

class Simulation extends BaseEventEmitter implements SimulationInterface
{
    /**
     * @var int
     */
    const EVENTS_COMPARE_IN_ORDER = 1;

    /**
     * @var int
     */
    const EVENTS_COMPARE_RANDOMLY = 2;

    /**
     * @var int
     */
    const STATE_PENDING = 0;

    /**
     * @var int
     */
    const STATE_SUCCEED = 1;

    /**
     * @var int
     */
    const STATE_FAILED = 2;

    /**
     * @var int
     */
    const STATE_SKIPPED = 4;

    /**
     * @var LoopExtendedInterface
     */
    private $loop;

    /**
     * @var callable(SimulationInterface)
     */
    private $scenario;

    /**
     * @var
     */
    private $events;

    /**
     * @var int
     */
    private $state;

    /**
     * @var string|null
     */
    private $stateMessage;

    /**
     * @var callable(SimulationInterface)
     */
    private $startCallback;

    /**
     * @var callable(SimulationInterface)
     */
    private $stopCallback;

    /**
     * @var
     */
    private $stopFlags;

    /**
     * @var array
     */
    private $params;

    /**
     * @param LoopExtendedInterface $loop
     */
    public function __construct(LoopExtendedInterface $loop)
    {
        $this->loop = $loop;
        $this->scenario = function() {};
        $this->state = static::STATE_PENDING;
        $this->events = [];
        $this->stateMessage = null;
        $this->skipMessage = null;
        $this->startCallback = function() {};
        $this->stopCallback = function() {};
        $this->stopFlags = false;
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->stop();

        unset($loop);
        unset($this->scenario);
        unset($this->events);
        unset($this->state);
        unset($this->stateMessage);
        unset($this->startCallback);
        unset($this->stopCallback);
        unset($this->stopFlags);
    }

    /**
     * @param string $key
     * @param mixed $val
     */
    public function setParam($key, $val)
    {
        $this->params[$key] = $val;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getParam($key)
    {
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    /**
     * @param callable(SimulationInterface) $scenario
     */
    public function setScenario(callable $scenario)
    {
        $this->scenario = $scenario;
    }

    /**
     * @return callable(SimulationInterface)|null $scenario
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * @return LoopInterface
     */
    public function getLoop()
    {
        return $this->loop;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return int
     */
    public function getStateMessage()
    {
        return $this->stateMessage;
    }

    /**
     *
     */
    public function begin()
    {
        $this->start();
    }

    /**
     *
     */
    public function done()
    {
        $this->state = static::STATE_SUCCEED;
        $this->stop();
    }

    /**
     *
     */
    public function skip($message)
    {
        $this->stateMessage = $message;
        $this->state = static::STATE_SKIPPED;
        $this->stop();
    }

    /**
     *
     */
    public function fail($message)
    {
        $this->stateMessage = $message;
        $this->state = static::STATE_FAILED;
        $this->stop();
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     * @param string $message
     */
    public function assertSame($expected, $actual, $message = "Assertion failed, expected \"%s\" got \"%s\"")
    {
        if ($expected !== $actual)
        {
            $stringExpected = (is_object($expected) || is_array($expected)) ? json_encode($expected) : (string) $expected;
            $stringActual   = (is_object($actual)   || is_array($actual))   ? json_encode($actual)   : (string) $actual;

            $this->fail(sprintf($message, $stringExpected, $stringActual));
        }
    }

    /**
     * @param string $name
     * @param mixed $data
     */
    public function expect($name, $data = [])
    {
        $this->events[] = new Event($name, $data);
    }

    /**
     * @return Event[]
     */
    public function getExpectations()
    {
        return $this->events;
    }

    /**
     * @param callable $callable
     */
    public function onStart(callable $callable)
    {
        $this->startCallback = $callable;
    }

    /**
     * @param callable $callable
     */
    public function onStop(callable $callable)
    {
        $this->stopCallback = $callable;
    }

    /**
     * @param string $model
     * @param mixed[] $config
     * @return object
     */
    public function reflect($model, $config = [])
    {
        foreach ($config as $key=>$value)
        {
            if ($value === 'Dazzle\Loop\Loop' || $value === 'Dazzle\Loop\LoopInterface')
            {
                $config[$key] = $this->getLoop();
            }
        }

        return (new ReflectionClass($model))->newInstanceArgs($config);
    }

    /**
     * @throws Exception
     */
    private function start()
    {
        $sim = $this;

        $scenario = $this->scenario;
        $scenario($sim);

        if ($this->stopFlags === true)
        {
            return;
        }

        $onStart = $this->startCallback;
        $loop = $this->loop;

        $loop->onStart(function() use($sim, $onStart) {
            $onStart($sim);
        });
        $loop->addTimer(5, function() use($sim) {
            $sim->fail('Timeout for test has been reached.');
        });

        $loop->start();
    }

    /**
     *
     */
    private function stop()
    {
        if ($this->loop !== null && $this->loop->isRunning())
        {
            $this->loop->stop();
        }

        if ($this->stopFlags === false)
        {
            $callable = $this->stopCallback;
            $callable($this);
        }

        $this->stopFlags = true;
    }
}
