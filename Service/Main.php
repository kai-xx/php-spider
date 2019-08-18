<?php
namespace Service;
use Bootstrap\SpiderIntoRedis;
use Db\RedisOwn;

class Main
{
    /**
     * @var array
     */
    public static $config;
    public static $redisPrefix;

    /**
     * Main constructor.
     * @param array $config
     * @param array $redisConfig
     */
    public function __construct(array $config, array $redisConfig)
    {
        self::$config = $config;
        self::$redisPrefix = $redisConfig['prefix'] ?? '';
    }

    /**
     * 获取所有需要获取下级URL的集合
     * @return array
     */
    public function getRedisListKey()
    {
        $key = "list_url-path-parent";
        $keys = RedisOwn::connect()->keys($key);
        return empty($keys) ? [] : $keys;
    }

    /**
     * 获取最末级URL
     */
    public function handleParentUrl(){
        $spider = new SpiderIntoRedis(self::$config);
        $type = true;
        while ($type === true){
            $key = "list_url-path-parent";
            $total = RedisOwn::connect()->lLen($key);
            $type = $total == 0 ? false : true;
            if ($type === false) continue;
            $url = RedisOwn::connect()->lpop($key);
            try {
                $spider->setUrl($url);
                $spider->getHomeUrl();
            } catch (\Exception $e) {
                RedisOwn::connect()->rpush($key, $url);
            }
        }
    }

    /**
     * 统计总数
     */
    public function getKeys(){
        $key = "list_*";
        $keys = RedisOwn::connect()->keys($key);
        foreach ($keys as $item){
            $k = str_replace(self::$redisPrefix, '', $item);
            $url = SpiderIntoRedis::decodeKey(str_replace(self::$redisPrefix . "list_", '', $item));
            $total =RedisOwn::connect()->lLen($k);
//            var_dump($total);exit;
            echo sprintf("链接为%s，共有%d 条记录", $url, $total) . PHP_EOL;
        }
        return $keys;
    }
}