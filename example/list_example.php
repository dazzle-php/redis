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
 * Redis List Commands
 * @see https://redis.io/commands#list
 */
$redis->on('start', function (Redis $redis) {
    //this one is not a command of redis list commands part
    $redis->flushDb();

    $redis->lPush('t_list', 1, 2, 3, 4, 5)->then(function ($value) {
        printf("Push %d elements to a new list\n", $value);
    });
    $redis->lPop('t_list')->then(function ($value) {
        printf("Pop \"%s\" elements from list\n", $value);
    });
    $redis->lIndex('t_list', 0)->then(function ($value) {
        printf("Index 0 element: \"%s\" elements from list\n", $value);

    });
    $redis->lInsert('t_list', 'after', 4, 10)->then(function ($_) {
        printf("New element: \"%s\" insert to list\n", '10');
    });
    $redis->lLen('t_list')->then(function ($value) {
        printf("Now list length: %d\n", $value);
    });
    $redis->lRange('t_list')->then(function ($value) {
        printf("All elements: %s\n", implode(',', $value));
    });
    $redis->lRem('t_list', 0, 4)->then(function ($value) {
        printf("Remove %d element(s) of that value equals 4\n", $value);
    });
    $redis->lSet('t_list', 0, 3)->then(function ($_) {
        printf("Set index 0 elements value to 3.");
    });
    $redis->lRange('t_list')->then(function ($value) {
        printf("In the end,the results should be: %s", implode(',', $value));

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