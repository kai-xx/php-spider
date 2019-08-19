<?php

namespace Tool;
class DetailTool extends Base
{
    public static $timeSourceHtml;
    public static $contentHtml;

    public function handle($content)
    {

    }

    /**
     * 获取分类
     * @param $content
     * @return mixed|void
     */
    public function getCategory($content)
    {
//        file_put_contents('a.html', $content);
        $catePatter = "/(?<=<div class=\"post_crumb\">)\s(.*)(?<!<\/div>)/";
        preg_match($catePatter, $content, $cateHtml);
        $patter = "/<a.*?>(.*?)<\/a>/i";
        preg_match_all($patter,$cateHtml[0],$category);
        return $category[1];
    }

    /**
     * 获取标题
     * @param $content
     * @return mixed|string|void
     */
    public function getTitle($content)
    {
        $patter = "/<h1>(.*?)<\/h1>/";
        preg_match($patter, $content, $title);
        return empty($title[1]) ? "" : $title[1];
    }

    /**
     * 获取原标题
     * @param $content
     * @return mixed|string|void
     */
    public function getOriginalTitle($content)
    {
        $patter = "/(?<=<p class=\"otitle\">)(.*?)(?<!<\/p>)/";
        preg_match($patter, $content, $title);
        return  empty($title[1]) ? "" : $title[1];
    }

    /**
     * 获取时间和来源HTML段
     * @param $content
     */
    public function getTimeSourceHtml($content){
        $patter = "/(?<=<div class=\"post_time_source\">)\s(.*)(?<!<\/div>)/";
        preg_match($patter, $content, $html);
        static::$timeSourceHtml = $html[1];
    }

    /**
     * 获取时间
     * @return mixed|string|void
     */
    public function getTime()
    {
        $patter = "/(\d*-\d*-\d* \d*:\d*:\d*)/";
        preg_match($patter, static::$timeSourceHtml, $time);
        return  empty($time[1]) ? "" : $time[1];
    }

    /**
     * 获取来源
     * @return mixed|string|void
     */
    public function getSource()
    {
        $patter = "/<a id=\"ne_article_source\" .*?>(.*)<\/a>/";
        preg_match($patter, static::$timeSourceHtml, $source);
        return empty($source[1]) ? "" : $source[1];
    }

    /**
     * 获取描述
     * @return string|void
     */
    public function getDesc()
    {
        return '';
    }

    /**
     * 获取内容
     * @return string|void
     */
    public function getContent()
    {
        $patter = "/(?<=<p>).*?(?=<\/p>)/";
        preg_match_all($patter, static::$contentHtml, $content);
        $result = implode('', $content[0]);
        return empty($result) ? "" : $result;
    }

    /**
     * 获取内容HTML代码
     * @param $content
     */
    public function getContentHtml($content){
        $patter = "/<div.*?id=\"endText\".*?>([\s\S]+?)<\/div>/";
        preg_match($patter, $content, $result);
        static::$contentHtml = $result[1];
    }

    /**
     * 获取内容中图片
     * @return mixed|void
     */
    public function getImageList()
    {
        $patter = "/<img src=\"(.*?)\".*?>/";
        preg_match($patter, static::$contentHtml, $image);
        return $image[1];
    }
    public function getPageLastUrl()
    {

    }
}