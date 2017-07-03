<?php

require __DIR__.'/../vendor/autoload.php';

if (!class_exists('Error'))
{
    class Error extends Exception
    {}
}

if (!defined('TEST_DB_REDIS_ENDPOINT'))
{
    define('TEST_DB_REDIS_ENDPOINT', getenv('TEST_DB_REDIS_ENDPOINT') ? getenv('TEST_DB_REDIS_ENDPOINT') : 'tcp://127.0.0.1:6379');
}

date_default_timezone_set('UTC');
