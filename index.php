<?php
use Bootstrap\Autoload;

// 设置时区
date_default_timezone_set('Asia/Shanghai');
// 严格开发模式
error_reporting( E_ALL );
// 永不超时
ini_set('max_execution_time', 0);
set_time_limit(0);
// 内存限制，如果外面设置的内存比 /etc/php/php-cli.ini 大，就不要设置了
if (intval(ini_get("memory_limit")) < 1024)
{
    ini_set('memory_limit', '1024M');
}
if( PHP_SAPI != 'cli' )
{
    exit("You must run the CLI environment\n");
}
define('APP_PATH', '../php-spider/');
//引入自动加载类
require './Bootstrap/Autoload.php';
//初始化自动加载
Autoload::init();

$url = "https://news.163.com/";
$config = [
    'url'=>$url,
    'main'=>"163.com",
    'postfix'=>['html'],
    "filter_main" => [
        'corp.163.com',
        'gb.corp.163.com'
    ]
];

$home = new \Service\Home($config);
$home->handle();
$main = new \Service\Main($config);
$main->getTheLastLevel();
