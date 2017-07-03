<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Redis\Redis;

$loop = new Loop(new SelectLoop());

// $redis = new Redis('tcp://127.0.0.1:6379', $loop);

$ret = [];

$loop = new Loop(new SelectLoop());

$redis = new Redis('192.168.99.100:32768', $loop);

$ret = [];

$redis->select(0);

$redis->flushDb();

$redis->set('test','Hello Kraken Redis!');

$redis->exists('test')->then(function ($response) {
    echo $response.PHP_EOL;
});

$redis->info(['cpu'])->then(function ($value) {
    global $ret;
    $ret[] = $value;
});

$redis->zAdd('k', [], ['0.002' => 'h','0.004' => 'l']);

$redis->zRange('k', 0, -1, true)->then(function ($response) {
    var_export($response);
    echo PHP_EOL;
});

$redis->zRemRangeByScore('k',0,1)->then(function ($response) {
    echo $response.PHP_EOL;
});

$redis->quit();

$loop->onStart(function () use ($redis, $loop) {

    $redis->dispatcher->on('error', function(\Exception $e) {
        echo $e->getMessage().PHP_EOL;
    });

    $redis->dispatcher->on('close', function () use ($loop){
        $loop->stop();
    });

    $redis->connect();
});

$loop->start();
