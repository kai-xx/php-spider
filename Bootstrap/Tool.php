<?php

namespace Bootstrap;

/**
 * 工具类
 * Class Tool
 * @package Bootstrap
 */
class Tool
{
    public static $url;

    /**
     * Tool constructor.
     * @param string $url
     */
    public function __construct(string $url){
        if(!preg_match("/^(http)s?/", $url)){
            $url = "http://".$url;
        }
        self::$url = $url;
    }

    /**
     * 获取页面信息
     * @param string $url
     * @return bool|string
     */
    public static function getUrlContent(string $url){
        $url = self::completionUrl($url);
        @$handle = fopen($url, "r");
        if(error_get_last()){//捕获异常（不一定是错误）
            $err = new \Exception("你的URL好像不对！要不换一个？");
            echo $err->getMessage();
//            Log::error(sprintf("URL为：%s，不合法，错误信息为：%s", $url, $err->getMessage()));
            return false;
        }
        if($handle){
            $content = stream_get_contents($handle,1024*1024);//将资源流读入字符串
            fclose($handle);
            return $content;
        }else{
            fclose($handle);
            return false;
        }
    }

    /**
     * 从html内容中筛选链接
     * @param string $web_content
     * @return mixed
     */
    public static function filterUrl(string $web_content){
        $reg_tag_a = '/<[a|A].*?href=[\'\"]{0,1}([^>\'\"\ ]*).*?>/';
        $result = preg_match_all($reg_tag_a,$web_content,$match_result);
        if($result){
            return $match_result[1];
        }
    }

    /**
     * 判断是否是完整的url
     * @param string $url
     * @return bool
     */
    public static function judgeURL(string $url){
        $url_info = parse_url($url);
        if(isset($url_info['scheme'])||isset($url_info['host'])){
            return true;
        }
        return false;
    }

    /**
     * 修正相对路径
     * @param string $base_url
     * @param $url_list
     * @return array|bool
     */
    public static function reviseUrl(string $base_url,$url_list){
        $url_info = parse_url($base_url);//分解url中的各个部分
        unset($base_url);
        $base_url = isset($url_info["scheme"])?$url_info["scheme"].'://':"";//$url_info["scheme"]为http、ftp等
        if(isset($url_info["user"]) && isset($url_info["pass"])){//记录用户名及密码的url
            $base_url .= $url_info["user"].":".$url_info["pass"]."@";
        }
        $base_url .= isset($url_info["host"])?$url_info["host"]:"";//$url_info["host"]域名
        if(isset($url_info["port"])){//$url_info["port"]端口，8080等
            $base_url .= ":".$url_info["port"];
        }
        $base_url .= isset($url_info["path"])?$url_info["path"]:"";//$url_info["path"]路径
        //目前为止，绝对路径前面已经组装完
        if(is_array($url_list)){
            foreach ($url_list as $url_item) {
                // if(preg_match('/^(http)s?/',$url_item)){
                if(self::judgeURL($url_item)){
                    //已经是完整的url
                    $result[] = $url_item;
                }else {
                    //不完整的url
                    $real_url = $base_url.$url_item;
                    $result[] = $real_url;
                }
            }
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 获取主域名
     * @param string $url
     * @return mixed
     */
    public static function getMain(string $url)
    {
        preg_match('/[\w][\w-]*\.(?:com\.cn|com|cn|co|net|org|gov|cc|biz|info)(\/|$)/isU', $url, $domain);
        return trim($domain[0]??'', "/");
    }

    public static function completionUrl($url){
        if(!preg_match("/^(http)s?/", $url)){
            $url = "http://".$url;
        }
        return $url;
    }
}