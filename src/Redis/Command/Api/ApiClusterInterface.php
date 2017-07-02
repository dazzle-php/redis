<?php

namespace Dazzle\Redis\Command\Api;

interface ApiClusterInterface
{
    /**
     * @doc https://redis.io/commands/cluster-addslots
     * @sinc 3.0.0
     * @param ...$slots
     * @return mixed
     */
    public function clusterAddSlots(...$slots);

    /**
     * @doc https://redis.io/commands/cluster-count-failure-reports
     * @since 3.0.0
     * @param $nodeId
     * @return mixed
     */
    public function clusterCountFailureReports($nodeId);

    /**
     * @doc https://redis.io/commands/cluster-countkeysinslot
     * @since 3.0.0
     * @param $slot
     * @return mixed
     */
    public function clusterCountKeysInSlot($slot);

    /**
     * @doc https://redis.io/commands/cluster-delslots
     * @since 3.0.0
     * @param ...$slots
     * @return mixed
     */
    public function clusterDelSlots(...$slots);

    /**
     * @doc https://redis.io/commands/cluster-failover
     * @since 3.0.0
     * @param $operation
     * @return mixed
     */
    public function clusterFailOver($operation);

    /**
     * @doc https://redis.io/commands/cluster-forget
     * @since 3.0.0
     * @param $nodeId
     * @return mixed
     */
    public function clusterForget($nodeId);

    /**
     * @doc https://redis.io/commands/cluster-getkeysinslot
     * @since 3.0.0
     * @param $slot
     * @param $count
     * @return mixed
     */
    public function clusterGetKeyInSlot($slot, $count);

    /**
     * @doc https://redis.io/commands/cluster-info
     * @since 3.0.0
     * @return mixed
     */
    public function clusterInfo();

    /**
     * @doc https://redis.io/commands/cluster-keyslot
     * @since 3.0.0
     * @param $key
     * @return mixed
     */
    public function clusterKeySlot($key);

    /**
     * @doc https://redis.io/commands/cluster-meet
     * @since 3.0.0
     * @param $ip
     * @param $port
     * @return mixed
     */
    public function clusterMeet($ip, $port);

    /**
     * @doc https://redis.io/commands/cluster-nodes
     * @since 3.0.0
     * @return mixed
     */
    public function clusterNodes();

    /**
     * @doc https://redis.io/commands/cluster-replicate
     * @since 3.0.0
     * @param $nodeId
     * @return mixed
     */
    public function clusterReplicate($nodeId);

    /**
     * @doc https://redis.io/commands/cluster-reset
     * @since 3.0.0
     * @param $mode
     * @return mixed
     */
    public function clusterReset($mode);

    /**
     * @doc https://redis.io/commands/cluster-saveconfig
     * @since 3.0.0
     * @return mixed
     */
    public function clusterSaveConfig();

    /**
     * @doc https://redis.io/commands/cluster-set-config-epoch
     * @since 3.0.0
     * @param $configEpoch
     * @return mixed
     */
    public function clusterSetConfigEpoch($configEpoch);

    /**
     * @doc https://redis.io/commands/cluster-setslot
     * @since 3.0.0
     * @param $command
     * @param $nodeId
     * @return mixed
     */
    public function clusterSetSlot($command, $nodeId);

    /**
     * @doc https://redis.io/commands/cluster-slaves
     * @since 3.0.0
     * @param $nodeId
     * @return mixed
     */
    public function clusterSlaves($nodeId);

    /**
     * @doc https://redis.io/commands/cluster-slots
     * @since 3.0.0
     * @return mixed
     */
    public function clusterSlots();

    /**
     * @doc https://redis.io/commands/readonly
     * @since 3.0.0
     * @return mixed
     */
    public function readOnly();

    /**
     * @doc https://redis.io/commands/readwrite
     * @since 3.0.0
     * @return mixed
     */
    public function readWrite();
}
