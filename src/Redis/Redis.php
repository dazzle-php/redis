<?php

namespace Dazzle\Redis;

use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;
use Dazzle\Redis\Command\Builder;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\PromiseInterface;
use Dazzle\Promise\Deferred;
use Dazzle\Throwable\Exception\RuntimeException;
use Error;

class Redis
{
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @param string $endpoint
     * @param LoopInterface $loop
     */
    public function __construct($endpoint, LoopInterface $loop)
    {
        $this->endpoint = $endpoint;
        $this->loop = $loop;
        $this->dispatcher = new Dispatcher($loop);
        $this->driver = $this->dispatcher->getDriver();
    }

    /**
     *
     */
    public function __destruct()
    {

    }

    /**
     * Pipe Redis request.
     *
     * @param Request $command
     * @return PromiseInterface
     */
    private function pipe(Request $command)
    {
        $request = new Deferred();
        $promise = $request->getPromise();
        if ($this->dispatcher->isEnding())
        {
            $request->reject(new RuntimeException('Connection closed'));
        } 
        else 
        {
            $payload = $this->driver->commands($command);

            $this->dispatcher->on('request', function () use ($payload) {
                $this->dispatcher->handleRequest($payload);
            });

            $this->dispatcher->appendRequest($request);
        }

        return $promise;
    }

    public function connect($config = [])
    {
        $this->dispatcher->watch($this->endpoint);
    }

    public function auth($password)
    {
        $command = Enum::AUTH;
        $args = [$password];

        return $this->pipe(Builder::build($command, $args));
    }

    public function append($key, $value)
    {
        $command = Enum::APPEND;
        $args = [$key, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function bgRewriteAoF()
    {
        $command = Enum::BGREWRITEAOF;

        return $this->pipe(Builder::build($command));
    }

    public function bgSave()
    {
        $command = Enum::BGSAVE;

        return $this->pipe(Builder::build($command));
    }

    public function bitCount($key, $start = 0, $end = 0)
    {
        $command = Enum::BITCOUNT;
        $args = [$key, $start, $end];

        return $this->pipe(Builder::build($command, $args));
    }

    public function bitField($key, $subCommand = null, ...$param)
    {
        $command = Enum::BITFIELD;
        switch ($subCommand = strtoupper($subCommand)) {
            case 'GET' : {
                list ($type, $offset) = $param;
                $args = [$subCommand, $type, $offset];
                break;
            }
            case 'SET' : {
                list ($type, $offset, $value) = $param;
                $args = [$subCommand, $type, $offset, $value];
                break;
            }
            case 'INCRBY' : {
                list ($type, $offset, $increment) = $param;
                $args = [$type, $offset, $increment];
                break;
            }
            case 'OVERFLOW' : {
                list ($behavior) = $param;
                $args = [$subCommand, $behavior];
                break;
            }
            default : {
                    $args = [];
                    break;
            }
        }
        $args = array_filter($args);

        return $this->pipe(Builder::build($command, $args));
    }

    public function bitOp($operation, $dstKey, $srcKey, ...$keys)
    {
        $command = Enum::BITOP;
        $args = [$operation, $dstKey, $srcKey];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    public function bitPos($key, $bit, $start = 0, $end = 0)
    {
        $command = Enum::BITPOS;
        $args = [$key, $bit, $start, $end];

        return $this->pipe(Builder::build($command, $args));
    }

    public function blPop(array $keys, $timeout)
    {
        $command = Enum::BLPOP;
        $keys[] = $timeout;
        $args = $keys;
        $promise = $this->pipe(Builder::build($command, $args));
        $promise = $promise->then(function ($value) {
            if (is_array($value)) {
                list($k,$v) = $value;

                return [
                    'key'=>$k,
                    'value'=>$v
                ];
            }

            return $value;
        });

        return $promise;
    }

    public function brPop(array $keys, $timeout)
    {
        $command = Enum::BRPOP;
        $keys[] = $timeout;
        $args = $keys;
        $promise = $this->pipe(Builder::build($command, $args));
        $promise = $promise->then(function ($value) {
            if (is_array($value)) {
                list($k,$v) = $value;

                return [
                    'key'=>$k,
                    'value'=>$v
                ];
            }

            return $value;
        });

        return $promise;
    }

    public function brPopLPush($src, $dst, $timeout)
    {
        $command = Enum::BRPOPLPUSH;
        $args = [$src, $dst, $timeout];

        return $this->pipe(Builder::build($command, $args));
    }

    public function decr($key)
    {
        $command = Enum::DECR;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function decrBy($key, $decrement)
    {
        $command = Enum::DECRBY;
        $args = [$key, $decrement];

        return $this->pipe(Builder::build($command, $args));
    }

    public function del($key,...$keys)
    {
        $command = Enum::DEL;
        $keys[] = $key;
        $args = $keys;

        return $this->pipe(Builder::build($command, $args));
    }

    public function discard()
    {
        $command = Enum::DISCARD;

        return $this->pipe(Builder::build($command));
    }

    public function dump($key)
    {
        $command = Enum::DUMP;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function exists($key, ...$keys)
    {
        $command = Enum::EXISTS;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    public function expire($key, $seconds)
    {
        $command = Enum::EXPIRE;
        $args = [$key, $seconds];

        return $this->pipe(Builder::build($command, $args));
    }

    public function expireAt($key, $timestamp)
    {
        $command = Enum::EXPIREAT;
        $args = [$key, $timestamp];

        return $this->pipe(Builder::build($command, $args));
    }

    public function get($key)
    {
        $command = Enum::GET;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function getBit($key, $offset)
    {
        $command = Enum::GETBIT;
        $args = [$key, $offset];

        return $this->pipe(Builder::build($command, $args));
    }

    public function getRange($key, $start, $end)
    {
        $command = Enum::GETRANGE;
        $args = [$key, $start, $end];

        return $this->pipe(Builder::build($command, $args));
    }

    public function getSet($key, $value)
    {
        $command = Enum::GETSET;
        $args = [$key, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function incr($key)
    {
        $command = Enum::INCR;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function incrBy($key, $increment)
    {
        $command = Enum::INCRBY;
        $args = [$key, $increment];

        return $this->pipe(Builder::build($command, $args));
    }

    public function incrByFloat($key, $increment)
    {
        $command = Enum::INCRBYFLOAT;
        $args = [$key, $increment];

        return $this->pipe(Builder::build($command, $args));
    }

    public function multi()
    {
        $command = Enum::MULTI;

        return $this->pipe(Builder::build($command));
    }

    public function persist($key)
    {
        $command = Enum::PERSIST;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function pExpire($key, $milliseconds)
    {
        $command = Enum::PEXPIRE;
        $args = [$key, $milliseconds];

        return $this->pipe(Builder::build($command, $args));
    }

    public function pExpireAt($key, $milliseconds)
    {
        $command = Enum::PEXPIREAT;
        $args = [$key, $milliseconds];

        return $this->pipe(Builder::build($command, $args));
    }

    public function sync()
    {
        $command = Enum::SYNC;

        return $this->pipe(Builder::build($command));
    }

    public function time()
    {
        $command = Enum::TIME;

        return $this->pipe(Builder::build($command));
    }

    public function touch($key, ...$keys)
    {
        $command = Enum::TOUCH;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    public function ttl($key)
    {
        $command = Enum::TTL;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function type($key)
    {
        $command = Enum::TYPE;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function unLink($key, ...$keys)
    {
        $command = Enum::UNLINK;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    public function unWatch()
    {
        // TODO: Implement unWatch() method.
        $command = Enum::UNWATCH;

        return $this->pipe(Builder::build($command));
    }

    public function wait($numSlaves, $timeout)
    {
        // TODO: Implement wait() method.
        $command = Enum::WAIT;
        $args = [$numSlaves, $timeout];

        return $this->pipe(Builder::build($command, $args));
    }

    public function watch($key, ...$keys)
    {
        // TODO: Implement watch() method.
        $command = Enum::WATCH;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    public function select($index)
    {
        $command = Enum::SELECT;
        $args = [$index];

        return $this->pipe(Builder::build($command, $args));
    }

    public function set($key, $value, array $options = [])
    {
        $command = Enum::SET;
        array_unshift($options, $key, $value);
        $args = $options;

        return $this->pipe(Builder::build($command, $args));
    }

    public function setBit($key, $offset, $value)
    {
        $command = Enum::SETBIT;
        $args = [$key, $offset, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function setEx($key, $seconds, $value)
    {
        $command = Enum::SETEX;
        $args = [$key, $seconds, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function setNx($key, $value)
    {
        $command = Enum::SETNX;
        $args = [$key, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function randomKey()
    {
        $command = Enum::RANDOMKEY;

        return $this->pipe(Builder::build($command));
    }

    public function readOnly()
    {
        $command = Enum::READONLY;

        return $this->pipe(Builder::build($command));
    }

    public function rename($key, $newKey)
    {
        $command = Enum::RENAME;
        $args = [$key, $newKey];

        return $this->pipe(Builder::build($command, $args));
    }

    public function renameNx($key, $newKey)
    {
        $command = Enum::RENAMENX;
        $args = [$key, $newKey];

        return $this->pipe(Builder::build($command, $args));
    }

    public function restore($key, $ttl, $value)
    {
        $command = Enum::RESTORE;
        $args = [$key, $ttl, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function ping($message = 'PING')
    {
        $command = Enum::PING;
        $args = [$message];

        return $this->pipe(Builder::build($command, $args));
    }

    public function quit()
    {
        $command = Enum::QUIT;
        $that = $this;

        return $this->pipe(Builder::build($command))->then(function ($_) use ($that) {
            $that->dispatcher->emit('disconnect');
        });
    }

    public function setRange($key, $offset, $value)
    {
        $command = Enum::SETRANGE;
        $args = [$key, $offset, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function pTtl($key)
    {
        $command = Enum::PTTL;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function pSetEx($key, $milliseconds, $value)
    {
        $command = Enum::PSETEX;
        $args = [$key, $milliseconds, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hDel($key, ...$fields)
    {
        $command = Enum::HDEL;
        $args = [$key];
        $args = array_merge($args, $fields);

        return $this->pipe(Builder::build($command, $args));
    }

    public function hGet($key, $field)
    {
        $command = Enum::HGET;
        $args = [$key, $field];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hGetAll($key)
    {
        $command = Enum::HGETALL;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args))->then(function ($value) {
            if (!empty($value)) {
                $tmp = [];
                $size = count($value);
                for ($i=0; $i<$size; $i+=2) {
                    $field = $value[$i];
                    $val = $value[$i+1];
                    $tmp[$field] = $val;
                }
                $value = $tmp;
            }
        
            return $value;
        });
    }

    public function hIncrBy($key, $field, $increment)
    {
        $command = Enum::HINCRBY;
        $args = [$key, $field, $increment];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hIncrByFloat($key, $field, $increment)
    {
        $command = Enum::HINCRBYFLOAT;
        $args = [$key, $field, $increment];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hKeys($key)
    {
        $command = Enum::HKEYS;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hLen($key)
    {
        $command = Enum::HLEN;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hMGet($key, ...$fields)
    {
        $command = Enum::HMGET;
        $args = [$key];
        $args = array_merge($args, $fields);

        return $this->pipe(Builder::build($command, $args));
    }

    public function hMSet($key, array $fvMap)
    {
        $command = Enum::HMSET;
        $args = [$key];
        if (!empty($fvMap)) {
            foreach ($fvMap as $field => $value) {
                $tmp[] = $field;
                $tmp[] = $value;
            }
            $fvMap = $tmp;        
        }
        $args = array_merge($args, $fvMap);

        return $this->pipe(Builder::build($command, $args));
    }

    public function hSet($key, $field, $value)
    {
        $command = Enum::HSET;
        $args = [$key, $field, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hSetNx($key, $filed, $value)
    {
        $command = Enum::HSETNX;
        $args = [$key, $filed, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hStrLen($key, $field)
    {
        $command = Enum::HSTRLEN;
        $args = [$key, $field];

        return $this->pipe(Builder::build($command, $args));
    }

    public function hVals($key)
    {
        $command = Enum::HVALS;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function geoAdd($key, array $coordinates)
    {
        // TODO: Implement geoAdd() method.
        $command = Enum::GEOADD;
        $args = [$key];
        $args = array_merge($args, $coordinates);

        return $this->pipe(Builder::build($command, $args));
    }

    public function geoHash($key, ...$members)
    {
        // TODO: Implement geoHash() method.
        $command = Enum::GEOHASH;
    }

    public function geoPos($key, ...$members)
    {
        // TODO: Implement geoPos() method.
        $command = Enum::GEOPOS;
        $args = [$key];
        $args = array_merge($args, $members);

        return $this->pipe(Builder::build($command, $args));
    }

    public function geoDist($key, $memberA, $memberB, $unit)
    {
        // TODO: Implement geoDist() method.
        $command = Enum::GEODIST;
        $args = [$key, $memberA, $memberB ,$unit];

        return $this->pipe(Builder::build($command, $args));
    }

    public function geoRadius($key, $longitude, $latitude, $unit, $command, $count, $sort)
    {
        // TODO: Implement geoRadius() method.
        $command = Enum::GEORADIUS;
        $args = [$key, $longitude, $latitude, $unit, $command, $count, $sort];

        return $this->pipe(Builder::build($command, $args));
    }

    public function geoRadiusByMember($key, $member, $unit, $command, $count, $sort, $store, $storeDist)
    {
        // TODO: Implement geoRadiusByMember() method.
        $command = Enum::GEORADIUSBYMEMBER;
        $args = [$key, $member, $unit, $command, $count, $sort, $store, $storeDist];

        return $this->pipe(Builder::build($command, $args));
    }

    public function pSubscribe(...$patterns)
    {
        // TODO: Implement pSubscribe() method.
        $command = Enum::PSUBSCRIBE;
        $args = $patterns;

        return $this->pipe(Builder::build($command, $args));
    }

    public function pubSub($command, array $args = [])
    {
        // TODO: Implement pubSub() method.
        $command = Enum::PUBSUB;

        return $this->pipe(Builder::build($command, $args));
    }

    public function publish($channel, $message)
    {
        // TODO: Implement publish() method.
        $command = Enum::PUBLISH;
        $args = [$channel, $message];

        return $this->pipe(Builder::build($command, $args));
    }

    public function pUnsubscribe(...$patterns)
    {
        // TODO: Implement pUnsubscribe() method.
        $command = Enum::PUNSUBSCRIBE;
        $args = $patterns;

        return $this->pipe(Builder::build($command, $args));
    }

    public function unSubscribe(...$channels)
    {
        // TODO: Implement unSubscribe() method.
        $command = Enum::UNSUBSCRIBE;
        $args = $channels;

        return $this->pipe(Builder::build($command, $args));
    }

    public function lIndex($key, $index)
    {
        // TODO: Implement lIndex() method.
        $command = Enum::LINDEX;
        $args = [$key, $index];

        return $this->pipe(Builder::build($command, $args));
    }

    public function lInsert($key, $action, $pivot, $value)
    {
        // TODO: Implement lInsert() method.
        $command = Enum::LINSERT;
        $args = [$key, $action, $pivot, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function lLen($key)
    {
        // TODO: Implement lLen() method.
        $command = Enum::LLEN;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function lPop($key)
    {
        // TODO: Implement lPop() method.
        $command = Enum::LPOP;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function lPush($key,...$values)
    {
        $command = Enum::LPUSH;
        array_unshift($values, $key);

        return $this->pipe(Builder::build($command, $values));
    }

    public function lPushX($key, $value)
    {
        $command = Enum::LPUSHX;
        $args = [$key, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function lRange($key, $start = 0, $stop = -1)
    {
        $command = Enum::LRANGE;
        $args = [$key, $start, $stop];

        return $this->pipe(Builder::build($command, $args));
    }

    public function lRem($key, $count, $value)
    {
        // TODO: Implement lRem() method.
        $command = Enum::LREM;
        $args = [$key, $count, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function lSet($key, $index, $value)
    {
        // TODO: Implement lSet() method.
        $command = Enum::LSET;
        $args = [$key, $index, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function lTrim($key, $start, $stop)
    {
        // TODO: Implement lTrim() method.
        $command = Enum::LTRIM;
        $args = [$key, $start, $stop];

        return $this->pipe(Builder::build($command, $args));
    }

    public function mGet($key, ...$values)
    {
        // TODO: Implement mGet() method.
        $command = Enum::MGET;
        $args = [$key];
        $args = array_merge($args, $values);

        return $this->pipe(Builder::build($command, $args));
    }

    public function mSet(array $kvMap)
    {
        // TODO: Implement mSet() method.
        $command = Enum::MSET;
        $args = $kvMap;

        return $this->pipe(Builder::build($command, $args));
    }

    public function monitor()
    {
        // TODO: Implement monitor() method.
        $command = Enum::MONITOR;

        return $this->pipe(Builder::build($command));
    }

    public function move($key, $db)
    {
        // TODO: Implement move() method.
        $command = Enum::MOVE;
        $args = [$key, $db];

        return $this->pipe(Builder::build($command, $args));
    }

    public function mSetNx($kvMap)
    {
        // TODO: Implement mSetNx() method.
        $command = Enum::MSETNX;
        $args = $kvMap;

        return $this->pipe(Builder::build($command, $args));
    }

    public function rPop($key)
    {
        $command = Enum::RPOP;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function rPopLPush($src, $dst)
    {
        // TODO: Implement rPopLPush() method.
        $command = Enum::RPOPLPUSH;
        $args = [$src, $dst];

        return $this->pipe(Builder::build($command, $args));
    }

    public function rPush($key, ...$values)
    {
        $command = Enum::RPUSH;
        $args = [$key];
        $args = array_merge($args, $values);

        return $this->pipe(Builder::build($command, $args));
    }

    public function rPushX($key, $value)
    {
        $command = Enum::RPUSHX;
        $args = [$key, $value];

        return $this->pipe(Builder::build($command, $args));
    }

    public function pFAdd($key, ...$elements)
    {
        // TODO: Implement pFAdd() method.
        $command = Enum::PFADD;
        $args = [$key];
        $args = array_merge($args, $elements);

        return $this->pipe(Builder::build($command, $args));
    }

    public function pFCount(...$keys)
    {
        // TODO: Implement pFCount() method.
        $command = Enum::PFCOUNT;
        $args = $keys;

        return $this->pipe(Builder::build($command, $args));
    }

    public function pFMerge(array $dsKeyMap)
    {
        // TODO: Implement pFMerge() method.
        $command = Enum::PFMERGE;
        $args = $dsKeyMap;

        return $this->pipe(Builder::build($command, $args));
    }

    public function clusterAddSlots(...$slots)
    {
        // TODO: Implement clusterAddSlots() method.
        $command = Enum::CLUSTER_ADDSLOTS;
        $args = $slots;

        return $this->pipe(Builder::build($command, $args));
    }

    public function clusterCountFailureReports($nodeId)
    {
        // TODO: Implement clusterCountFailureReports() method.
        $command = Enum::CLUSTER_COUNT_FAILURE_REPORTS;
        $args = [$nodeId];

        return $this->pipe(Builder::build($command, $args));
    }

    public function clusterCountKeysInSlot($slot)
    {
        // TODO: Implement clusterCountKeysInSlot() method.
        $command = Enum::CLUSTER_COUNTKEYSINSLOT;
        $args = $slot;

        return $this->pipe(Builder::build($command, $args));
    }

    public function clusterDelSlots(...$slots)
    {
        // TODO: Implement clusterDelSlots() method.
        $command = Enum::CLUSTER_DELSLOTS;
        $args = $slots;

        return $this->pipe(Builder::build($command, $args));
    }

    public function clusterFailOver($operation)
    {
        // TODO: Implement clusterFailOver() method.
    }

    public function clusterForget($nodeId)
    {
        // TODO: Implement clusterForget() method.
    }

    public function clusterGetKeyInSlot($slot, $count)
    {
        // TODO: Implement clusterGetKeyInSlot() method.
    }

    public function clusterInfo()
    {
        // TODO: Implement clusterInfo() method.
        $command = Enum::CLUSTER_INFO;

        return $this->pipe(Builder::build($command));
    }

    public function clusterKeySlot($key)
    {
        // TODO: Implement clusterKeySlot() method.
        $command = Enum::CLUSTER_KEYSLOT;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function clusterMeet($ip, $port)
    {
        // TODO: Implement clusterMeet() method.
        $command = Enum::CLUSTER_MEET;
        $args = [$ip, $port];

        return $this->pipe(Builder::build($command, $args));
    }

    public function clusterNodes()
    {
        // TODO: Implement clusterNodes() method.
    }

    public function clusterReplicate($nodeId)
    {
        // TODO: Implement clusterReplicate() method.
    }

    public function clusterReset($mode)
    {
        // TODO: Implement clusterReset() method.
    }

    public function clusterSaveConfig()
    {
        // TODO: Implement clusterSaveConfig() method.
    }

    /**
     * @inheritDoc
     */
    public function clusterSetConfigEpoch($configEpoch)
    {
        // TODO: Implement clusterSetConfigEpoch() method.
    }

    /**
     * @inheritDoc
     */
    public function clusterSetSlot($command, $nodeId)
    {
        // TODO: Implement clusterSetSlot() method.
        $command = Enum::CLUSTER_SETSLOT;
        $args = [$command, $nodeId];

        return $this->pipe(Builder::build($command, $args));
    }

    /**
     * @inheritDoc
     */
    public function clusterSlaves($nodeId)
    {
        // TODO: Implement clusterSlaves() method.
        $command = Enum::CLUSTER_SLAVES;
        $args = [$nodeId];

        return $this->pipe(Builder::build($command, $args));
    }

    /**
     * @inheritDoc
     */
    public function clusterSlots()
    {
        // TODO: Implement clusterSlots() method.
        $command = Enum::CLUSTER_SLOTS;

        return $this->pipe(Builder::build($command));
    }

    public function flushAll()
    {
        $command = Enum::FLUSHALL;

        return $this->pipe(Builder::build($command));
    }

    public function flushDb()
    {
        $command = Enum::FLUSHDB;

        return $this->pipe(Builder::build($command));
    }

    public function info($section = [])
    {
        $command = Enum::INFO;

        return $this->pipe(Builder::build($command, $section))->then(function ($value) {
            if ($value) {
                $ret = explode(PHP_EOL, $value);
                $handled = [];
                $lastKey = '';
                foreach ($ret as $_ => $v) {
                    if (($pos = strpos($v, '#')) !== false) {
                        $lastKey = strtolower(substr($v,$pos+2));
                        $handled[$lastKey] = [];
                        continue;
                    }
                    $statMap = explode(':', $v);
                    if ($statMap[0]) {
                        list($name, $stat) = explode(':', $v);
                        $handled[$lastKey][$name] = $stat;
                    }
                }

                return $handled;
            }

            return $value;
        });
    }

    public function zAdd($key, array $options = [], array $scoreMembers = [])
    {
        $command = Enum::ZADD;
        $args = array_merge([$key], $options);
        if (!empty($scoreMembers)) {
            foreach ($scoreMembers as $score => $member) {
                $args[] = (float) $score;
                $args[] = $member;
            }
        }

        return $this->pipe(Builder::build($command, $args));
    }

    public function zCard($key)
    {
        $command = Enum::ZCARD;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function zCount($key, $min, $max)
    {
        $command = Enum::ZCOUNT;
        $args = [$key, $min, $max];

        return $this->pipe(Builder::build($command, $args));
    }

    public function zIncrBy($key, $increment, $member)
    {
        $command = Enum::ZINCRBY;
        $args = [$key, $increment, $member];

        return $this->pipe(Builder::build($command, $args));
    }

    public function zInterStore($dst, $numKeys)
    {
        // TODO: Implement zInterStore() method.
        $command = Enum::ZINTERSTORE;
        $args = [$dst, $numKeys];

        return $this->pipe(Builder::build($command, $args));
    }

    public function zLexCount($key, $min, $max)
    {
        $command = Enum::ZLEXCOUNT;
        $args = [$key, $min, $max];

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRange($key, $star = 0, $stop = -1, $withScores = false)
    {
        $command = Enum::ZRANGE;
        $args = [$key, $star, $stop];
        if ($withScores) {
            $args[] = 'WITHSCORES';

            return $this->pipe(Builder::build($command, $args))->then(function ($value) {
                $len = count($value);
                $ret = [];
                for ($i=0; $i<$len; $i+=2) {
                    $ret[$value[$i]] = $value[$i+1];
                }

                return $ret;
            });
        }

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRangeByLex($key, $min, $max, array $options = [])
    {
        $command = Enum::ZRANGEBYLEX;
        $args = [$key, $min, $max];
        $args = array_merge($args,$options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRevRangeByLex($key, $max, $min, array $options = [])
    {
        $command = Enum::ZREVRANGEBYLEX;
        $args = [$key, $max,$min];
        $args = array_merge($args,$options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRangeByScore($key, $min, $max, array $options = [])
    {
        $command = Enum::ZRANGEBYSCORE;
        $args = [$key, $min,$max];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRank($key, $member)
    {
        $command = Enum::ZRANK;
        $args = [$key,$member];

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRem($key, ...$members)
    {
        $command = Enum::ZREM;
        $args = array_merge([$key], $members);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRemRangeByLex($key, $min, $max, $options = [])
    {
        $command = Enum::ZREMRANGEBYLEX;
        $args = [$key, $min, $max];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRemRangeByRank($key, $start, $stop)
    {
        $command = Enum::ZREMRANGEBYRANK;
        $args = [$key, $start,$stop];

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRemRangeByScore($key, $min, $max, $options = [])
    {
        $command = Enum::ZREMRANGEBYSCORE;
        $args = [$key, $min, $max];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRevRange($key, $start, $stop, array $options = [])
    {
        $command = Enum::ZREVRANGE;
        $args = [$key, $start, $stop];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRevRangeByScore($key, $max, $min, array $options = [])
    {
        $command = Enum::ZREVRANGEBYSCORE;
        $args = [$key,$max,$min];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zRevRank($key, $member)
    {
        $command = Enum::ZREVRANK;
        $args = [$key,$member];

        return $this->pipe(Builder::build($command, $args));
    }

    public function zScore($key, $member)
    {
        $command = Enum::ZSCORE;
        $args = [$key,$member];

        return $this->pipe(Builder::build($command, $args));
    }

    public function scan($cursor, array $options = [])
    {
        $command = Enum::SCAN;
        $args = [$cursor];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function sScan($key, $cursor, array $options = [])
    {
        // TODO: Implement sScan() method.
        $command = Enum::SSCAN;
        $args = [$key, $cursor];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function hScan($key, $cursor, array $options = [])
    {
        // TODO: Implement hScan() method.
        $command = Enum::HSCAN;
        $args = [$key, $cursor];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function zScan($key, $cursor, array $options = [])
    {
        // TODO: Implement zScan() method.
        $command = Enum::ZSCAN;
        $args = [$key , $cursor];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function sInter(...$keys)
    {
        // TODO: Implement sInter() method.
        $command = Enum::SINTER;
        $args = $keys;

        return $this->pipe(Builder::build($command, $args));
    }

    public function sInterStore($dst, ...$keys)
    {
        // TODO: Implement sInterStore() method.
        $command = Enum::SINTERSTORE;
        $args = [$dst];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    public function sIsMember($key, $member)
    {
        // TODO: Implement sIsMember() method.
        $command = Enum::SISMEMBER;
        $args = [$key ,$member];

        return $this->pipe(Builder::build($command, $args));
    }

    public function slaveOf($host, $port)
    {
        // TODO: Implement slaveOf() method.
        $command = Enum::SLAVEOF;
        $args = [$host, $port];

        return $this->pipe(Builder::build($command, $args));
    }

    public function sLowLog($command, array $args = [])
    {
        // TODO: Implement sLowLog() method.
        $command = Enum::SLOWLOG;
        $args = array_merge([$command],$args);

        return $this->pipe(Builder::build($command, $args));
    }

    public function sMembers($key)
    {
        // TODO: Implement sMembers() method.
        $command = Enum::SMEMBERS;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function sMove($src, $dst, $members)
    {
        // TODO: Implement sMove() method.
        $command = Enum::SMOVE;
        $args = [$src, $dst];
        $args = array_merge( $args, $members);

        return $this->pipe(Builder::build($command, $args));
    }

    public function sort($key, array $options = [])
    {
        // TODO: Implement sort() method.
        $command = Enum::SORT;
        $args = [$key];
        $args = array_merge($args, $options);

        return $this->pipe(Builder::build($command, $args));
    }

    public function sPop($key, $count)
    {
        // TODO: Implement sPop() method.
        $command = Enum::SPOP;
        $args = [$key, $count];

        return $this->pipe(Builder::build($command, $args));
    }

    public function sRandMember($key, $count)
    {
        // TODO: Implement sRandMember() method.
        $command = Enum::SRANDMEMBER;
        $args = [$key, $count];

        return $this->pipe(Builder::build($command, $args));
    }

    public function sRem($key, ...$members)
    {
        // TODO: Implement sRem() method.
        $command = Enum::SREM;
        $args = [$key];
        $args = array_merge($args, $members);

        return $this->pipe(Builder::build($command, $args));
    }

    public function strLen($key)
    {
        // TODO: Implement strLen() method.
        $command = Enum::STRLEN;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function subscribe(...$channels)
    {
        // TODO: Implement subscribe() method.
        $command = Enum::SUBSCRIBE;
        $args = $channels;

        return $this->pipe(Builder::build($command, $args));
    }

    public function sUnion(...$keys)
    {
        // TODO: Implement sUnion() method.
        $command = Enum::SUNION;
        $args = $keys;

        return $this->pipe(Builder::build($command, $args));
    }

    public function sUnionStore($dst, ...$keys)
    {
        // TODO: Implement sUnionStore() method.
        $command = Enum::SUNIONSTORE;
        $args = [$dst];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    public function sWapBb($opt, $dst, ...$keys)
    {
        // TODO: Implement sWapBb() method.
        $command = Enum::SWAPDB;
        $args = [$opt, $dst];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    public function sAdd($key, ...$members)
    {
        // TODO: Implement sAdd() method.
        $command = Enum::SADD;
        $args = [$key];
        $args = array_merge($args, $members);

        return $this->pipe(Builder::build($command, $args));
    }

    public function save()
    {
        // TODO: Implement save() method.
        $command = Enum::SAVE;

        return $this->pipe(Builder::build($command));
    }

    public function sCard($key)
    {
        // TODO: Implement sCard() method.
        $command = Enum::SCARD;
        $args = [$key];

        return $this->pipe(Builder::build($command, $args));
    }

    public function sDiff(...$keys)
    {
        // TODO: Implement sDiff() method.
        $command = Enum::SDIFF;
        $args = $keys;

        return $this->pipe(Builder::build($command, $args));
    }

    public function sDiffStore($dst, ...$keys)
    {
        // TODO: Implement sDiffStore() method.
        $command = Enum::SDIFFSTORE;
        $args = [$dst];
        $args = array_merge($args, $keys);

        return $this->pipe(Builder::build($command, $args));
    }

    /**
     * @inheritDoc
     */
    public function hExists($key, $field)
    {
        // TODO: Implement hExists() method.
        $command = Enum::HEXISTS;
        $args = [$key, $field];

        return $this->pipe(Builder::build($command, $args));
    }

    /**
     * @inheritDoc
     */
    public function readWrite()
    {
        // TODO: Implement readWrite() method.
        $command = Enum::READWRITE;

        return $this->pipe(Builder::build($command));
    }

    /**
     * @inheritDoc
     */
    public function zUnionScore($dst, $numKeys)
    {
        // TODO: Implement zUnionScore() method.
        $command = Enum::ZUNIIONSCORE;
        $args = [$dst, $numKeys];

        return $this->pipe(Builder::build($command, $args));
    }
};