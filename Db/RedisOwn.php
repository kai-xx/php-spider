<?php

namespace Db;

use Bootstrap\Log;

class RedisOwn
{
    private static $_instance;
    private static $config;
    private static $pid;
    private function __construct()
    {

    }

    /**
     *
     */
    private static function getConfig(){
        $config = require './Config/Redis.php';
        static::$config['host'] = $config['host'] ?? "127.0.0.1";
        static::$config['port'] = $config['port'] ?? 6379;
        static::$config['password'] = $config['password'] ?? null;
        static::$config['prefix'] = $config['prefix'] ?? null;
        return;
    }

    /**
     * @param int $pid
     * @return \Redis
     * @throws \Exception
     */
    public static function connect($pid = 0){
        self::$pid = $pid;
        if (empty(static::$_instance[$pid])){
            static::getConfig();
            static::$_instance[$pid] = RedisOwn::conn();
        }
        return static::$_instance[$pid];
    }

    /**
     * @return \Redis
     * @throws \Exception
     */
    private static function conn(){
        try{
            $redis = new \Redis();
            $redis->connect(static::$config['host'], static::$config['port']);
            if (!empty(static::$config['password']))
                $redis->auth(static::$config['password']);
            if ($redis->ping()){
                $redis->select(0);
                $redis->setOption(\Redis::OPT_PREFIX, static::$config['prefix']);  //设置表前缀为spider_
                $redis->exec();
                return $redis;
            }else{
                Log::error("redis连接失败");
                throw new \Exception("redis连接失败");
            }
        }catch (\RedisException $e){
            Log::error("redis连接失败，" . $e->getMessage());
            throw new \Exception("redis连接失败，" . $e->getMessage());
        }catch (\Exception $e){
            Log::error("redis连接失败，" . $e->getMessage());
            throw new \Exception("redis连接失败，" . $e->getMessage());
        }
    }

    private function __clone()
    {
    }

}
