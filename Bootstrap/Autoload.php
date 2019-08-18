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
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $filePath = APP_PATH . $class_path . '.php';

        if (is_file($filePath)) {
            require $filePath;
        }
    }
}