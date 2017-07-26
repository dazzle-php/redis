<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Redis\Redis;

$loop = new Loop(new SelectLoop());
//create a new redis client
$endpoint = @$argv[1]?:'tcp://127.0.0.1:6379';
$redis = new Redis($endpoint, $loop);
//send piped requests when redis client start
$redis->on('start', function(Redis $redis) {

    $redis->flushDb();

    $redis->set('test','Hello Dazzle Redis!')->then(function ($value) {
        printf('result is %s'.PHP_EOL, $value);
    });

    $redis->get('test')->then(function ($value) {
        printf('result is %s'.PHP_EOL, $value);
    });

    $redis->end();
});
//redis client stop handler
$redis->on('stop', function() use ($loop) {
    echo 'stop right now'.PHP_EOL;
    $loop->stop();
});
//redis client error handler
$redis->on('error', function(Exception $ex) {
    printf('error message is %s'.PHP_EOL, $ex->getMessage());
});
//set redis client run when global loop start
$loop->onStart(function () use ($redis) {
    $redis->start();
});
//then the global loop run...
$loop->start();


