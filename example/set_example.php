<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Promise\Promise;
use Dazzle\Redis\Redis;

$loop = new Loop(new SelectLoop());
//create a new redis client
$endpoint = @$argv[1]?:'tcp://127.0.0.1:6379';
$redis = new Redis($endpoint, $loop);

/**
 * Redis Set Commands
 * @see https://redis.io/commands#set
 */
$redis->on('start', function (Redis $redis) {
    $redis->flushDb();

    $redis->sAdd('t_set', 1, 2, 3, 4, 5)->then(function ($value) {
        printf("Add %d elements to set\n", $value);
    });

    $redis->sMembers('t_set')->then(function ($value) {
        printf("All elements are: %s\n", implode(',', $value));
    });

    $redis->sAdd('t_a_set', 4, 5, 6, 7, 8, 9)->then(function ($value) {
        printf("Then add %d elements to another set\n", $value);
    });

    $redis->sMembers('t_a_set')->then(function ($value) {
        printf("All elements are: %s\n", implode(',', $value));
    });

    $redis->sDiff('t_set', 't_a_set')->then(function ($value) {
        printf("Difference set [t_set - t_a_set]  is: %s\n", implode(',', $value));
    });

    $redis->sUnion('t_set', 't_a_set')->then(function ($value) {
        printf("Intersection [t_set %s t_a_set]  is: %s\n", "\u{2229}" ,implode(',', $value));
    });

    $redis->sMove('t_set', 't_a_set', 1)->then(function ($value) use ($redis) {
        printf("Move %d elements from t_set to t_a_set\n", $value);
    });

    $redis->sCard('t_set')->then(function ($value) use ($redis) {
        printf("Now t_set has %d elements,", $value);
    });

    $redis->sCard('t_a_set')->then(function ($value) use ($redis) {
        printf("t_set has %d elements\n", $value);
    });

    $redis->sRem('t_set', 2)->then(function ($value) {
        printf("Remove %d element from t_set and it is %s\n", $value, '"2"');
    });

    $redis->sIsMember('test', 2)->then(function ($value) {
        printf("In the end %s %s exist in t_set too\n", '"2"', $value ? 'is' : 'is not');
    });

    //And more than those commands that you could use...
    $redis->end();
});

//stop global loop cauz that loop run for only one component(redis)
//if use more than one dazzle component,do not use like that
$redis->on('stop', function () use ($loop) {
    $loop->stop();
});

$redis->on('error', function (Exception $e) use ($redis) {
    echo $e->getMessage().PHP_EOL;
    $redis->stop();
});

$loop->onStart(function () use ($redis) {
    $redis->start();
});

$loop->start();