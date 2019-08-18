<?php
namespace Service;
use Bootstrap\SpiderIntoRedis;

class Home
{
    /**
     * @var array
     */
    public static $config;

    /**
     * Home constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        self::$config = $config;
    }

    /**
     * @throws \Exception
     */
    public function handle(){
        try{
            $spider = new SpiderIntoRedis(self::$config);
            $spider->getHomeUrl();
        }catch (\Exception $e){
            throw new \Exception("抓取异常，"  . $e->getMessage());
        }

    }
}