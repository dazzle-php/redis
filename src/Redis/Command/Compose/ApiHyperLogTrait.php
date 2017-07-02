<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiHyperLogTrait
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
    public function pFAdd($key, ...$elements)
    {
        // TODO: Implement pFAdd() method.
        $command = Enum::PFADD;
        $args = [$key];
        $args = array_merge($args, $elements);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pFCount(...$keys)
    {
        // TODO: Implement pFCount() method.
        $command = Enum::PFCOUNT;
        $args = $keys;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pFMerge(array $dsKeyMap)
    {
        // TODO: Implement pFMerge() method.
        $command = Enum::PFMERGE;
        $args = $dsKeyMap;

        return $this->dispatch(Builder::build($command, $args));
    }
}
