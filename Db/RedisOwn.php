<?php

namespace Db;

class RedisOwn
{
    private static $_instance;
    private static $config;
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
        return;
    }
    public static function connect(){

        if (empty(static::$_instance)){
            static::getConfig();
            static::$_instance = RedisOwn::conn();
        }
        return static::$_instance;
    }

    /**
     * @return Redis
     * @throws \Exception
     */
    private static function conn(){
        try{
            $redis = new Redis();
            $redis->connect(static::$config['host'], static::$config['port']);
            if (!empty(static::$config['password']))
                $redis->auth(static::$config['password']);
            if ($redis->ping()){
                return $redis;
            }else{
                throw new \Exception("redis连接失败");
            }
        }catch (\RedisException $e){
            throw new \Exception("1redis连接失败，" . $e->getMessage());
        }catch (\Exception $e){
            throw new \Exception("redis连接失败，" . $e->getMessage());
        }
    }
    private function __clone()
    {

    }

}
