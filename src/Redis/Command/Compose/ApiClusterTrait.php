<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiClusterTrait
{
    /**
     * @param Request $request
     * @return mixed
     */
    abstract function dispatch(Request $request);

    /**
     * @override
     * @inheritDoc
     */
    public function clusterAddSlots(...$slots)
    {
        // TODO: Implement clusterAddSlots() method.
        $command = Enum::CLUSTER_ADDSLOTS;
        $args = $slots;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterCountFailureReports($nodeId)
    {
        // TODO: Implement clusterCountFailureReports() method.
        $command = Enum::CLUSTER_COUNT_FAILURE_REPORTS;
        $args = [$nodeId];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterCountKeysInSlot($slot)
    {
        // TODO: Implement clusterCountKeysInSlot() method.
        $command = Enum::CLUSTER_COUNTKEYSINSLOT;
        $args = $slot;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterDelSlots(...$slots)
    {
        // TODO: Implement clusterDelSlots() method.
        $command = Enum::CLUSTER_DELSLOTS;
        $args = $slots;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterFailOver($operation)
    {
        // TODO: Implement clusterFailOver() method.
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterForget($nodeId)
    {
        // TODO: Implement clusterForget() method.
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterGetKeyInSlot($slot, $count)
    {
        // TODO: Implement clusterGetKeyInSlot() method.
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterInfo()
    {
        // TODO: Implement clusterInfo() method.
        $command = Enum::CLUSTER_INFO;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterKeySlot($key)
    {
        // TODO: Implement clusterKeySlot() method.
        $command = Enum::CLUSTER_KEYSLOT;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterMeet($ip, $port)
    {
        // TODO: Implement clusterMeet() method.
        $command = Enum::CLUSTER_MEET;
        $args = [$ip, $port];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterNodes()
    {
        // TODO: Implement clusterNodes() method.
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterReplicate($nodeId)
    {
        // TODO: Implement clusterReplicate() method.
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterReset($mode)
    {
        // TODO: Implement clusterReset() method.
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterSaveConfig()
    {
        // TODO: Implement clusterSaveConfig() method.
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterSetConfigEpoch($configEpoch)
    {
        // TODO: Implement clusterSetConfigEpoch() method.
    }

    /**
     * @inheritDoc
     */
    public function clusterSetSlot($command, $nodeId)
    {
        // TODO: Implement clusterSetSlot() method.
        $command = Enum::CLUSTER_SETSLOT;
        $args = [$command, $nodeId];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterSlaves($nodeId)
    {
        // TODO: Implement clusterSlaves() method.
        $command = Enum::CLUSTER_SLAVES;
        $args = [$nodeId];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function clusterSlots()
    {
        // TODO: Implement clusterSlots() method.
        $command = Enum::CLUSTER_SLOTS;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function readOnly()
    {
        // TODO: Implement readOnly() method.
        $command = Enum::READONLY;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function readWrite()
    {
        // TODO: Implement readWrite() method.
        $command = Enum::READWRITE;

        return $this->dispatch(Builder::build($command));
    }
}