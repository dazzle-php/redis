<?php

namespace Dazzle\Redis\Command;

use Dazzle\Redis\Driver\Request;

class Builder
{
    public static function build($command, $args = [])
    {
        return new Request($command, $args);
    }
}