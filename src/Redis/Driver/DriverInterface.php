<?php

namespace Dazzle\Redis\Driver;

use Clue\Redis\Protocol\Model\Request;

interface DriverInterface
{
    public function commands(Request $request);
}