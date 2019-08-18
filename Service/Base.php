<?php

namespace Service;


use Bootstrap\SpiderIntoRedis;

class Base
{
    public static $redisPrefix;

    public function key($key){
        return str_replace(static::$redisPrefix, '', $key);
    }

    public function keyUrl($key){
        return str_replace(static::$redisPrefix . "list_", '', $key);
    }

    public function url($key, $path){
        return SpiderIntoRedis::decodeKey($this->keyUrl($key)) . $path;
    }
}