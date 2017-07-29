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
 * Redis Hyperloglog Commands
 * @see https://redis.io/commands#hyperloglog
 */
$redis->on('start', function (Redis $redis) {
    $redis->flushDb();

    $redis->pFAdd('t_hll',1, 2, 3, 4, 5, 6)->then(function ($value) {
        printf("Add [t_hll]: %s\n", $value > 0? 'OK' : 'Failed');
    });
    $redis->pFAdd('t_hll_1', 7, 8, 9)->then(function ($value) {
        printf("Add another [t_hll_1]: %s\n", $value > 0? 'OK' : 'Failed');
    });
    $redis->pFCount('t_hll')->then(function ($value) {
        printf("Count log(s) for [t_hll]: %s\n", $value);
    });
    $redis->pFMerge('t_hll', 't_hll_1')->then(function ($value) {
        printf("Merge [t_hll_1] into [t_hll_1] log(s): %s\n", $value);
    });
    $redis->pFCount('t_hll')->then(function ($value) {
        printf("Count log(s) for [t_hll] again: %s\n", $value);
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