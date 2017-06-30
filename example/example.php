<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Kraken\Loop\Loop;
use Kraken\Redis\Client;
use Kraken\Loop\Model\SelectLoop;

$loop = new Loop(new SelectLoop());

$client = new Client('192.168.99.100:32768', $loop);

$ret = [];

$client->flushDb();

$client->set('test','Hello Kraken Redis!')->then(function ($value) {
    global $ret;
    $ret[] = $value;
});

$client->get('test')->then(function ($value) {
    global $ret;
    $ret[] = $value;
});

$client->info(['cpu'])->then(function ($value) {
    global $ret;
    $ret[] = $value;
});

$client->run();

var_export($ret);