<?php

namespace Dazzle\Redis\Driver;

use Clue\Redis\Protocol\Model\Request as ClueRequest;

class Request extends ClueRequest implements RequestInterface
{
    // TODO maybe public fields for performance?
}
