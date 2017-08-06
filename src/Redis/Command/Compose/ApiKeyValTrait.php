<?php

namespace Dazzle\Redis\Command\Compose;

use Dazzle\Redis\Command\Builder;
use Dazzle\Redis\Command\Enum;
use Dazzle\Redis\Driver\Request;

trait ApiKeyValTrait
{
    /**
     * @param Request $request
     * @return mixed
     */
    abstract function dispatch(Request $request);

    /**
     * @override
     * @inheritDoc
     */
    public function append($key, $value)
    {
        $command = Enum::APPEND;
        $args = [$key, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function bitCount($key, $start = 0, $end = -1)
    {
        $command = Enum::BITCOUNT;
        $args = [$key, $start, $end];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function bitField($key, $subCommand, ...$param)
    {
        $command = Enum::BITFIELD;
        $subCommand = strtoupper($subCommand);
        //TODO: control flow improvement
        switch ($subCommand) {
            case 'GET' : {
                @list ($type, $offset) = $param;
                $args = [$key, $subCommand, $type, $offset];
                break;
            }
            case 'SET' : {
                @list ($type, $offset, $value) = $param;
                $args = [$key, $subCommand, $type, $offset, $value];
                break;
            }
            case 'INCRBY' : {
                @list ($type, $offset, $increment) = $param;
                $args = [$key, $type, $offset, $increment];
                break;
            }
            case 'OVERFLOW' : {
                @list ($behavior) = $param;
                $args = [$key, $subCommand, $behavior];
                break;
            }
            default : {
                $args = [];
                break;
            }
        }

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function bitOp($operation, $dstKey, $srcKey, ...$keys)
    {
        $command = Enum::BITOP;
        $args = [$operation, $dstKey, $srcKey];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function bitPos($key, $bit, $start = 0, $end = -1)
    {
        $command = Enum::BITPOS;
        $args = [$key, $bit, $start, $end];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function decr($key)
    {
        $command = Enum::DECR;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function decrBy($key, $decrement)
    {
        $command = Enum::DECRBY;
        $args = [$key, $decrement];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function get($key)
    {
        $command = Enum::GET;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getBit($key, $offset)
    {
        $command = Enum::GETBIT;
        $args = [$key, $offset];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getRange($key, $start, $end)
    {
        $command = Enum::GETRANGE;
        $args = [$key, $start, $end];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getSet($key, $value)
    {
        $command = Enum::GETSET;
        $args = [$key, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function incr($key)
    {
        $command = Enum::INCR;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function incrBy($key, $increment)
    {
        $command = Enum::INCRBY;
        $args = [$key, $increment];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function incrByFloat($key, $increment)
    {
        $command = Enum::INCRBYFLOAT;
        $args = [$key, $increment];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function set($key, $value, array $options = [])
    {
        $command = Enum::SET;
        array_unshift($options, $key, $value);
        $args = $options;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function setBit($key, $offset, $value)
    {
        $command = Enum::SETBIT;
        $args = [$key, $offset, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function setEx($key, $seconds, $value)
    {
        $command = Enum::SETEX;
        $args = [$key, $seconds, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function setNx($key, $value)
    {
        $command = Enum::SETNX;
        $args = [$key, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function setRange($key, $offset, $value)
    {
        $command = Enum::SETRANGE;
        $args = [$key, $offset, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pSetEx($key, $milliseconds, $value)
    {
        $command = Enum::PSETEX;
        $args = [$key, $milliseconds, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function mGet($key, ...$keys)
    {
        $command = Enum::MGET;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function mSet(array $kvMap)
    {
        //TODO: change the param $kvMap to ...$kv,cauz map not allow duplicate key
        $command = Enum::MSET;
        $args = [];
        if (!empty($kvMap)) {
            foreach ($kvMap as $key => $val) {
                $args[] = $key;
                $args[] = $val;
            }
        }

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function mSetNx($kvMap)
    {
        $command = Enum::MSETNX;
        $args = [];
        if (!empty($kvMap)) {
            foreach ($kvMap as $key => $val) {
                $args[] = $key;
                $args[] = $val;
            }
        }

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function strLen($key)
    {
        $command = Enum::STRLEN;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function del($key,...$keys)
    {
        $command = Enum::DEL;
        $keys[] = $key;
        $args = $keys;

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function dump($key)
    {
        $command = Enum::DUMP;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function exists($key, ...$keys)
    {
        $command = Enum::EXISTS;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function expire($key, $seconds)
    {
        $command = Enum::EXPIRE;
        $args = [$key, $seconds];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function expireAt($key, $timestamp)
    {
        $command = Enum::EXPIREAT;
        $args = [$key, $timestamp];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function persist($key)
    {
        $command = Enum::PERSIST;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pExpire($key, $milliseconds)
    {
        $command = Enum::PEXPIRE;
        $args = [$key, $milliseconds];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pExpireAt($key, $milTimestamp)
    {
        $command = Enum::PEXPIREAT;
        $args = [$key, $milTimestamp];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function touch($key, ...$keys)
    {
        $command = Enum::TOUCH;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function ttl($key)
    {
        $command = Enum::TTL;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function type($key)
    {
        $command = Enum::TYPE;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function unLink($key, ...$keys)
    {
        $command = Enum::UNLINK;
        $args = [$key];
        $args = array_merge($args, $keys);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function wait($numSlaves, $timeout)
    {
        $command = Enum::WAIT;
        $args = [$numSlaves, $timeout];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function randomKey()
    {
        $command = Enum::RANDOMKEY;

        return $this->dispatch(Builder::build($command));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rename($key, $newKey)
    {
        $command = Enum::RENAME;
        $args = [$key, $newKey];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function renameNx($key, $newKey)
    {
        $command = Enum::RENAMENX;
        $args = [$key, $newKey];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function restore($key, $ttl, $value)
    {
        $command = Enum::RESTORE;
        $args = [$key, $ttl, $value];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pTtl($key)
    {
        $command = Enum::PTTL;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function move($key, $db)
    {
        $command = Enum::MOVE;
        $args = [$key, $db];

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function scan($cursor, array $options = [])
    {
        $command = Enum::SCAN;
        $args = [$cursor];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function sort($key, array $options = [])
    {
        $command = Enum::SORT;
        $args = [$key];
        $args = array_merge($args, $options);

        return $this->dispatch(Builder::build($command, $args));
    }

    /**
     * @override
     * @inheritDoc
     */
    public function keys($key = '*')
    {
        $command = Enum::KEYS;
        $args = [$key];

        return $this->dispatch(Builder::build($command, $args));
    }
}
