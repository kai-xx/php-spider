<?php

namespace Bootstrap;


use Db\RedisOwn;

class SpiderIntoRedis
{
    // todo 1 获取页面所有链接 2 分类链接 3 获取三级链接
    // todo 1 获取地址中 一级地址 2 根据一级地址 分类地址
    // todo 1 设置一个链接的爬取时间
    /**
     * 爬取URL
     * @var string
     */
    public static $url;
    /**
     * 爬取URL一级域名，过滤其他网站URL
     * 只抓去本一级域名下URL
     * @var string
     */
    public static $main;
    /**
     * 最终页面后缀名 .php .html ……
     * @var array
     */
    public static $postfix;
    /**
     *  需过滤域名
     * @var array
     */
    public static $filterMain;

    /**
     * Spider constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (!isset($config['url'])) {
            Log::error("域名未定义");
            return false;
        }
        if (!isset($config['main'])) {
            Log::error("主域名未定义");
            return false;
        }
        if (!isset($config['postfix'])) {
            Log::error("后缀未定义");
            return false;
        }
        self::$url = $config['url'];
        self::$main = $config['main'];
        self::$postfix = $config['postfix'];
        self::$filterMain = $config['filter_main'] ?? [];

    }
    public function setUrl($url){
        self::$url = $url;
    }
    public function getHomeUrl()
    {
        try{
            $content = Tool::getUrlContent(self::$url);
            $urlArray = Tool::filterUrl($content);
            foreach ($urlArray as $item){
                if (empty($item)) continue;
                $main = Tool::getMain($item);
                if (!empty($main) && $main == self::$main){
                    $param = explode(".",$item);
                    if (in_array(end($param), self::$postfix)){
                        // 写入redis 最终抓取队列
                        $this->addRedis($item);
                    }else{
                        //  写入父级URL队列
                        $this->addRedis($item, false);
                    }
                }
            }
        }catch (\Exception $e){
            throw new \Exception("抓取异常，"  . $e->getMessage());
        }
    }

    /**
     * url写入redis中
     * @param string $url
     * @param bool $last
     * @return bool
     */
    public function addRedis(string $url, bool $last = true)
    {
        $parse = parse_url($url);
        $scheme = $parse['scheme'] ?? '';
        $host = $parse['host'] ?? '';
        $path = $parse['path'] ?? '';
        if (in_array($host, static::$filterMain)){
            return false;
        }
        if ($last === true){
            if (empty($host) || empty($path) || empty($scheme)) {
                Log::error(sprintf("域名%s非法，parse_url函数解析异常，解析结果为：%s", $url, json_encode($parse)));
                return false;
            }

            $key = self::encodeKey($scheme . "://" . $host);
        }else{
            $key = "url-path-parent";
            $path = self::encodeKey($url);
        }
        $skey =  "index_" . $key;
        $check = RedisOwn::connect()->SISMEMBER($skey, $path);
        if ($check === false){
            $indexResult = RedisOwn::connect()->sadd($skey, $path);
            if ($indexResult){
                $lkey =  "list_" . $key;
                $result = RedisOwn::connect()->rpush($lkey, $path);
                if (!$result){
                    Log::error(sprintf("域名写入redis队列失败，key为%s，url为：%s", $lkey, $url));
                    return false;
                }
            }else{
                Log::error(sprintf("域名写入redis集合失败，key为%s，url为：%s", $skey, $url));
                return false;
            }
        }
    }
    public static function encodeKey($key){
        return base64_encode($key);
    }
    public static function decodeKey($key){
        return base64_decode($key);
    }
}