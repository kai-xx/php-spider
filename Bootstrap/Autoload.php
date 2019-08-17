<?php

namespace Bootstrap;
class Autoload
{
    public static $loader;
    private function __construct()
    {
        spl_autoload_register([$this,
            'import']);

    }

    public static function init()
    {
        if (self::$loader === null){
            self::$loader = new self();
        }
        return self::$loader;
    }

    public function import($className)
    {
        $filePath = $className . '.php';
        $filePath = '/Db/RedisOwn.php';
        echo $filePath . PHP_EOL;
        var_dump(is_file("../".$filePath));exit;
        if (is_file($filePath)) {
            var_dump($filePath);exit;
            require $filePath;
        }
    }
}