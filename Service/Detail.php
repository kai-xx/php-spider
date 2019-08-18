<?php
namespace Service;
use Bootstrap\SpiderIntoRedis;
use Db\RedisOwn;
class Detail extends Base
{
    public static $url;
    public static $config;
    public static $redisPrefix;
    public function __construct(array $config, array $redisConfig)
    {
        static::$config = $config;
        static::$redisPrefix = $redisConfig['prefix'] ?? '';
    }

    public function setUrl($url)
    {
        self::$url = $url;
    }

    public function handleDetail($key)
    {
        $spider = new SpiderIntoRedis(static::$config);
        $k = $this->key($key);
        $path = RedisOwn::connect()->lpop($k);
        RedisOwn::connect()->rpush($k, $path);
        $url = $this->url($key, $path);
        $spider->setUrl($url);
        var_dump($url);exit;
        $content = $spider->getHomeUrl(true);
        echo $content;
    }

    public function getCategory()
    {
        <div class="post_crumb">
                <a href="http://www.163.com/">网易首页</a> &gt; <a href="http://news.163.com/">新闻中心</a> &gt; <a href="http://discover.163.com">探索新闻</a> &gt; 正文
    </div>

        $patter = ""
    }

    public function getTime()
    {
        
    }

    public function getSource()
    {
        
    }

    public function getDesc()
    {
        
    }

    public function getContent()
    {
        
    }
    public function ()
    {
        
    }
    public function getContent($url)
    {

    }

    public function getPageLastUrl()
    {

    }
}