<?php
define('APP_PATH', '../php-spider/');
//引入自动加载类
require './Bootstrap/Autoload.php';
//初始化自动加载
\Bootstrap\Autoload::init();
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
        'product.auto.163.com'
    ]
];
$main = new \Service\Main($config);
$main->getKeys();