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
 * Redis Hash Commands
 * @see https://redis.io/commands#hash
 */
$redis->on('start', function (Redis $redis) {
    $redis->flushDb();

    $redis->hSet('t_key','f', 1)->then(function ($value) {
        printf("Set hash set filed \"f\"=1: %s\n", $value>0?'OK':'Failed');
    });
    $redis->hGet('t_key', 'f')->then(function ($value) {
        printf("Get hash set filed \"f\": %s\n", $value);
    });
    $redis->hGetAll('t_key')->then(function ($value) {
        printf("Get all fileds %s and values: %s\n",
            implode(',', array_keys($value)),
            implode(',', array_values($value))
        );
    });
    $redis->hMSet('t_key', [
        'f_1' => 1,
        'f_2' => 2,
    ])->then(function ($value) {
        printf("Multi set: %s\n", $value);
    });
    $redis->hMGet('t_key', 'f_1', 'f_2')->then(function ($value) {
        printf("Multi get: %s\n", implode(',', $value));
    });
    $redis->hExists('t_key', 'f_1')->then(function ($value) {
        printf("[t_key] filed \"f\" exist: %s\n", $value > 0 ? 'Y':'N');
    });
    $redis->hIncrBy('t_key', 'f', 5)->then(function ($value) {
        printf("1 increment by 5: %s\n", $value);
    });
    $redis->hVals('t_key')->then(function ($value) {
        printf("All values of hashes sets: %s\n", implode(',', $value));
    });
    $redis->hDel('t_key', 'f')->then(function ($value) {
        printf("Delete: %s\n", $value > 0 ? 'OK' : "Failed");
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