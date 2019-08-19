<?php
/**
 * 多进程
 */
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
    exit("You must run the CLI environment" . PHP_EOL);
}
define('APP_PATH', '../spider/');
//引入自动加载类
require './Bootstrap/Autoload.php';

$redisConfig = require './Config/Redis.php';
//初始化自动加载
Autoload::init();
\Bootstrap\Log::info("爬虫脚本开始执行");
$url = "https://news.163.com/";
$config = [
    'url'=>$url,
    'main'=>"163.com",
    'postfix'=>['html'],
    "filter_main" => [
        'corp.163.com',
        'gb.corp.163.com',
        'vip.open.163.com',
        'y.163.com',
        'hongcai.163.com',
        'study.163.com',
        'product.auto.163.com',
        'tech.163.com',
        'bbs2.lady.163.com'
    ]
];
\Bootstrap\Log::info("开始获取网站信息");
$home = new \Service\Home($config);
$home->handle();
\Bootstrap\Log::info("开始处理网站链接");
$main = new \Service\Main($config, $redisConfig);

const NEWLINE = "\n\n";

if (strtolower(php_sapi_name()) != 'cli') {
    die("请在cli模式下运行");
}

echo "当前进程：" . getmypid() . NEWLINE;

$pid = pcntl_fork(); //fork出子进程

$mainType = false;
if ($pid == -1) { // 创建错误，返回-1
    \Bootstrap\Log::info("进程fork失败");

} else if ($pid) { // $pid > 0, 如果fork成功，返回子进程id

    // 父进程逻辑
    $mainType = true;
    $main->handleParentUrl();
    \Bootstrap\Log::info("网站链接处理完毕");
    $mainType = false;

} else { // $pid = 0
    // 子进程逻辑
    while ($mainType === true){
        $keys = $main->getKeys();
        \Bootstrap\Log::info("开始处理详情页数据");
        $detail = new \Service\Detail($config, $redisConfig);
        foreach ($keys as $key){
            $detail->handleDetail($key);
        }
    }
}
\Bootstrap\Log::info("爬虫脚本执行完毕");

