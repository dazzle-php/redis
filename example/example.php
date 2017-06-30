<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Redis\Redis;

$loop = new Loop(new SelectLoop());

// $redis = new Redis('192.168.99.100:32769', $loop);
$redis = new Redis('tcp://127.0.0.1:6379', $loop);

$ret = [];

$redis->on('start', function(Redis $redis) {

    $redis->flushDb()->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->set('test1','test1');

    $redis->set('test2','test2');

    $redis->set('test','Hello Dazzle Redis!')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->get('test')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->append('test', 'Make PHP Awesome')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->get('test')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->ping()->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->exists('test','test1','test2')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->bitPos('test',5,10,20)->then(function ($value) {
        //todo : fix
        global $ret;
        $ret[] = $value;
    });

    $redis->expire('test',1)->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->info(['cpu'])->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->dump('test')->then(function ($value) use ($redis) {
        global $ret;
        $ret[] = $value;
        $redis->restore('test',0,$value)->then(function ($value) {
            global $ret;
            $ret[] = $value;
        });
    });

    $redis->touch('f','f1','f2','f3')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });
});

    $redis->rename('test','new_test')->then(function ($value) use ($redis) {
       if ($value == 'OK') {
           global $ret;
           $ret[] = 'RENAME OK';
       }
    });

    $redis->ttl('new_test')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->del('test','test1','test2')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->randomKey()->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->type('test1')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->unLink('new_test')->then(function ($value) {
        global $ret;
        $ret[] = $value;
    });

    $redis->end();
});

$redis->on('stop', function(Redis $redis) use(&$ret) {
    var_export($ret);
});

$redis->on('error', function($ex) {
    var_dump($ex);
});

$loop->onTick(function() use($redis) {
    $redis->start();
});

$loop->start();


