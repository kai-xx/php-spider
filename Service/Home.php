<?php
namespace Service;
use Bootstrap\SpiderIntoRedis;

class Home
{
    public static $config;
    public function __construct(array $config)
    {
        self::$config = $config;
    }
    public function handle(){
        try{
            $spider = new SpiderIntoRedis(self::$config);
            $spider->getHomeUrl();
        }catch (\Exception $e){
            throw new \Exception("抓取异常，"  . $e->getMessage());
        }

    }
}