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
 * Redis Keys and String Commands
 * @see https://redis.io/commands#generic
 * @see https://redis.io/commands#string
 */
$redis->on('start', function (Redis $redis) {
    $redis->flushDb();
    //normal string opts
    $redis->set('t_key', 'Hello Dazzle Redis!', ['ex', 60])->then(function ($value) {
        printf("Set [t_key] equals \"Hello Dazzle Redis!\": %s\n", $value);
    });
    $redis->append('t_key', 'XD')->then(function ($value) {
        printf("Append \"XD\" to [t_key]: %s\n", $value>0?'OK':'Failed');
    });
    $redis->get('t_key')->then(function ($value) {
        printf("Get [t_key]: %s\n", $value);
    });
    $redis->strLen('t_key')->then(function ($value) {
        printf("String length: %s\n", $value);
    });
    //multi opts
    $redis->mSet([
        't_key_1' => 1,
        't_key_2' => 2
    ])->then(function ($value) {
        printf("Multi set [t_key_1, t_key_2] keys %s\n", $value);
    });
    $redis->mGet('t_key_1', 't_key_2')->then(function ($value) {
        printf("Multi get [t_key_1, t_key_2]: %s\n", implode(',', $value));
    });
    //increment and decrement
    $redis->incrBy('t_key_1', 5)->then(function ($value) {
        printf("The result of 1 increment by 5: %s\n", $value);
    });

    $redis->decr('t_key_1')->then(function ($value) {
        printf("The result of 6 increment by 1: %s\n", $value);
    });

    //bit opts
    $redis->setBit('t_b_1', 0, 1);
    $redis->setBit('t_b_2', 0, 1);
    $redis->bitOp('AND', 'answer', 't_b_1', 't_b_2');
    $redis->getBit('answer', 0)->then(function ($value) {
        printf("The answer of \"1&1\" is: %d\n", $value);
    });

    //generic key opts
    $redis->exists('t_b_1', 't_b_2', 't_key_1')->then(function ($value) {
        printf("%s given key(s) is exist\n", $value);
    });
    $redis->expire('t_key_1', 60)->then(function ($value) {
        printf("Expire result: %s\n", $value);
    });
    $redis->expireAt('t_key_2', time() + 60)->then(function ($value) {
        printf("Expire at unix timestamp + 60 result: %s\n", $value);
    });
    $redis->persist('t_key')->then(function ($value) {
        printf("Persist result: %s\n", $value > 0 ? 'OK' : 'Failed');
    });
    $redis->type('t_key')->then(function ($value) {
        printf("Type: %s\n", $value);
    });
    $redis->ttl('t_key_1')->then(function ($value) {
        printf("TTL: %s(secs)\n", $value);
    });
    $redis->del('t_key', 't_key_1', 't_key_2', 'answer')->then(function ($value) {
        printf("Delete %d key(s)\n", $value);
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