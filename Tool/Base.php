<?php


namespace Tool;


abstract class Base
{
    public static $timeSourceHtml;
    public static $contentHtml;
    public function getCategory($content){}
    public function getTitle($content){}
    public function getOriginalTitle($content){}
    public function getTimeSourceHtml($content){}
    public function getTime(){}
    public function getSource(){}
    public function getDesc(){}
    public function getContent(){}
    public function getContentHtml($content){}
    public function getImageList(){}
}