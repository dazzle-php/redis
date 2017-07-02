<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Kraken\Loop\Loop;
use Kraken\Redis\Client;
use Kraken\Loop\Model\SelectLoop;

$loop = new Loop(new SelectLoop());

$client = new Client('192.168.99.100:32768', $loop);

$ret = [];

$client->select(0);

$client->flushDb();

$client->set('test','Hello Kraken Redis!');

$client->exists('test')->then(function ($response) {
    echo $response.PHP_EOL;
});

$client->info(['cpu'])->then(function ($value) {
    global $ret;
    $ret[] = $value;
});

$client->zAdd('k', [], ['0.002' => 'h','0.004' => 'l']);

$client->zRange('k', 0, -1, true)->then(function ($response) {
    var_export($response);
    echo PHP_EOL;
});

$client->zRemRangeByScore('k',0,1)->then(function ($response) {
    echo $response.PHP_EOL;
});

$client->quit();

$loop->onStart(function () use ($client, $loop) {

    $client->dispatcher->on('error', function(\Exception $e) {
        echo $e->getMessage().PHP_EOL;
    });

    $client->dispatcher->on('close', function () use ($loop){
        $loop->stop();
    });

    $client->connect();
});

$loop->start();