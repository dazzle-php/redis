<?php

namespace Dazzle\Redis\Command;

interface CommandInterface extends
    Api\ApiChannelInterface,
    Api\ApiClusterInterface,
    Api\ApiConnInterface,
    Api\ApiCoreInterface,
    Api\ApiGeospatialInterface,
    Api\ApiHyperLogInterface,
    Api\ApiKeyValInterface,
    Api\ApiListInterface,
    Api\ApiSetInterface,
    Api\ApiSetHashInterface,
    Api\ApiSetSortedInterface,
    Api\ApiTransactionInterface
{}