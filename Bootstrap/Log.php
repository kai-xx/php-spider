<?php

namespace Bootstrap;

/**
 * 日志类
 * Class Log
 * @package Bootstrap
 */
class Log
{
    /**
     * log文件地址
     * @var string
     */
    public static $log_file = "./Log/php-spider.log";
    /**
     * 日志级别
     * @var string
     */
    public static $type;
    /**
     * 日志内容
     * @var string
     */
    public static $message;

    /**
     * @param string $msg
     */
    public static function note(string $msg)
    {
        self::$message = $msg;
        self::$type = 'note';
        self::message($msg);
    }

    /**
     * @param string $msg
     */
    public static function debug(string $msg){
        self::$message = $msg;
        self::$type = 'debug';
        self::message();
    }
    /**
     * @param string $msg
     */
    public static function info(string $msg)
    {
        self::$message = $msg;
        self::$type = 'info';
        self::message($msg);
    }
    /**
     * @param string $msg
     */
    public static function error(string $msg)
    {
        self::$message = $msg;
        self::$type = 'error';
        self::message($msg);
    }

    /**
     * 记录日志
     * @return bool
     */
    public static function message(){
        if (!in_array(self::$type,[
            'note',
            'debug',
            'info',
            'error'
        ])) return false;
        $msg = sprintf("%s | %s | %s",
            date("Y-m-d H:i:s"),
            self::$type,
            self::$message) . PHP_EOL;
        echo $msg;
        file_put_contents(self::$log_file, $msg, FILE_APPEND | LOCK_EX);
    }

}