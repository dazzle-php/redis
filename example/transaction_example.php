<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Redis\Redis;

$loop = new Loop(new SelectLoop());
//create a new redis client
$endpoint = @$argv[1]?:'tcp://127.0.0.1:6379';
$redis = new Redis($endpoint, $loop);

$redis->on('start', function (Redis $redis) {
    $redis->multi()->then(function ($value) {
        printf("Transactions start %s\n", $value);
    });
    //declare transactions
    $redis->set('1', 1);
    $redis->get('1');
    //execute transactions
    $redis->exec()->then(function ($value) {
        printf("Transactions executed results: %s\n", implode(',', $value));
    });

    $redis->end();
});

$redis->on('stop', function () use ($loop) {
   $loop->stop();
});

$loop->onStart(function () use ($redis) {
    $redis->start();
});

$loop->start();