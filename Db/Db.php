<?php


namespace Db;


use Bootstrap\Log;

class Db
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
        $config = require './Config/Database.php';
        static::$config['connection'] = $config['connection'] ?? "mysql";
        static::$config['host'] = $config['host'] ?? "127.0.0.1";
        static::$config['port'] = $config['port'] ?? 3306;
        static::$config['database'] = $config['database'] ?? '';
        static::$config['username'] = $config['username'] ?? 'root';
        static::$config['password'] = $config['password'] ?? '';
        static::$config['prefix'] = $config['prefix'] ?? '';
        return;
    }
    public static function connect(){
        if (empty(static::$_instance)){
            static::getConfig();
            static::$_instance = Db::conn();
        }
        return static::$_instance;
    }

    /**
     * @return \PDO
     */
    private static function conn(){
        try {
            $dsn = sprintf("%s:host=%s:%s;dbname=%s;charset=utf8mb4",
                static::$config['connection'],
                static::$config['host'],
                static::$config['port'],
                static::$config['database']
            );
            $pdo = new \PDO($dsn, static::$config['username'], static::$config['password']); //初始化一个PDO对象
            return $pdo;
        } catch (\PDOException $e) {
            Log::error("Error!: " . $e->getMessage());
        }
    }

    private function __clone()
    {
    }

}