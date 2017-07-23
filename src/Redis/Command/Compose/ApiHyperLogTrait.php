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
        $command = Enum::PFCOUNT;
        $args = $keys;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pFMerge($dstKey, ...$srcKeys)
    {
        $command = Enum::PFMERGE;
        $args = array_merge([$dstKey], $srcKeys);

        return $this->dispatch(Builder::build($command, $args));
    }
}
