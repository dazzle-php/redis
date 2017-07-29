<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Redis\Redis;

$loop = new Loop(new SelectLoop());
//create a new redis client
$endpoint = @$argv[1]?:'tcp://127.0.0.1:6379';
$redis = new Redis($endpoint, $loop);
/**
 * Redis Server Core Commands
 * @see https://redis.io/commands#server
 */
$redis->on('start', function (Redis $redis) {
    $redis->info()->then(function ($value) {
        var_export($value);
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