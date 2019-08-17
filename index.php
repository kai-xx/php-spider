<?php
use Db\RedisOwn;
use Bootstrap\Autoload;
//引入自动加载类
require './Bootstrap/Autoload.php';
//初始化自动加载
Autoload::init();
$redis = RedisOwn::connect();

var_dump($redis->keys("*"));