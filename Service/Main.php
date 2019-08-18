<?php
namespace Service;
use Bootstrap\SpiderIntoRedis;
use Bootstrap\Tool;
use Db\RedisOwn;

class Main
{
    public static $config;
    public static $redisPrefix;

    public function __construct(array $config)
    {
        $redisConfig = require './Config/Redis.php';
        self::$config = $config;
        self::$redisPrefix = $redisConfig['prefix'] ?? '';
    }

    public function getRedisListKey()
    {
        $key = "list_url-path-parent";
        $keys = RedisOwn::connect()->keys($key);
        return empty($keys) ? [] : $keys;
    }

    public function getTheLastLevel()
    {
        $spider = new SpiderIntoRedis(self::$config);
        $type = true;
        while ($type === true) {
            $keys = $this->getRedisListKey();
            if (empty($keys)) break;
            foreach ($keys as $item) {
                $item = str_replace(self::$redisPrefix, '', $item);
                $total = RedisOwn::connect()->lLen($item);
                $type = $total == 0 ? false : true;
                if ($type === false) continue;
                try {
                    $url = RedisOwn::connect()->lpop($item);
                    $url = $this->getUrl($item, $url, $spider);
                    $spider->setUrl($url);
                    $spider->getHomeUrl();
                } catch (\Exception $e) {
                    RedisOwn::connect()->rpush($item, $url);
                }
            }
        }
    }

    public function getUrl($key, $url, SpiderIntoRedis $obj)
    {
        $item = str_replace('list_', '', $key);
        $decodeKey = $obj::decodeKey($item);
        return $decodeKey . $url;
    }
}