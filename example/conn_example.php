<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Redis\Redis;
//create a global loop
$loop = new Loop(new SelectLoop());
//create a new redis client
$endpoint = @$argv[1]?:'tcp://127.0.0.1:6379';
$redis = new Redis($endpoint, $loop);
/**
 * Redis Connection Commands
 * @see https://redis.io/commands#connection
 */
$redis->on('start', function () use ($redis) {
    $redis->auth('no password')->then(function ($value) {
        printf("Auth result is %s\n", $value);
    }, function (Exception $e) {
        printf("Auth is failed cauz %s\n", $e->getMessage());
    });

    $redis->select(0)->then(function ($value) {
        printf("Select result is %s\n", $value);
    });

    $redis->ping()->then(function ($value) {
        printf("Hello %s message\n", $value);
    });

    $redis->quit()->then(function ($value) {
        printf("Quit result is %s\n", $value);
    });
});

$redis->on('stop', function () use ($loop) {
    $loop->stop();
});

$loop->onStart(function () use ($redis) {
    $redis->start();
});

$loop->start();

