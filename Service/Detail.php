<?php
namespace Service;
use Bootstrap\Log;
use Bootstrap\SpiderIntoRedis;
use Bootstrap\Tool;
use Db\RedisOwn;
use Model\Category;
use Model\Content;
use Tool\DetailTool;

class Detail extends Base
{
    public static $url;
    public static $config;
    public static $redisPrefix;
    public static $timeSourceHtml;
    public static $contentHtml;
    public function __construct(array $config, array $redisConfig)
    {
        static::$config = $config;
        static::$redisPrefix = $redisConfig['prefix'] ?? '';
    }

    /**
     * 设置URL
     * @param $url
     */
    public function setUrl($url)
    {
        self::$url = $url;
    }

    /**
     * 执行主体
     * @param $key
     * @throws \Exception
     */
    public function handleDetail($key)
    {
        $spider = new SpiderIntoRedis(static::$config);
        $k = $this->key($key);
        // path --- /photoview/00AJ0003/668977.html
        $path = RedisOwn::connect()->lpop($k);
        RedisOwn::connect()->rpush($k, $path);
        // url --- http://ent.163.com/photoview/00AJ0003/668979.html
        $url = $this->url($key, $path);
//        $url = "https://news.163.com/19/0818/18/EMSOPTKK000189FH.html";
        $spider->setUrl($url);
//        $content = file_get_contents('a.html');
        $content = $spider->getHomeUrl(true);
        $detailTool = new DetailTool();
        $category = $detailTool->getCategory($content);
        $detailTool->getTimeSourceHtml($content);
        $detailTool->getContentHtml($content);
        $categoryId = $this->insertCategory($category);
        $imageList = $detailTool->getImageList();
        $result = $this->insertContent($content, $categoryId, $detailTool);
        if (!$result){
            $time = Tool::counter(SpiderIntoRedis::encodeKey($url));
            if ($time < 5){
                RedisOwn::connect()->rpush($k, $path);
            }
        }
        return;
    }

    /**
     * 写入分类
     * @param $category
     * @return bool|int
     */
    public function insertCategory($category)
    {
        $parentId = 0;
        foreach ($category as $key => $value){
            $query = Category::findOneByName($value);
            if (empty($query)){
                $data['name'] = $value;
                $data['level'] = $key;
                $data['parent_id'] = $parentId;
                $parentId = Category::insert($data);
            }else{
                $parentId = $query['id'];
            }
        }
        return $parentId;
    }

    /**
     * 写入内容
     * @param $content
     * @param $categoryId
     * @param DetailTool $detailTool
     * @return bool
     */
    public function insertContent($content, $categoryId, DetailTool $detailTool){

        $data['title'] = $detailTool->getTitle($content);
        $info = Content::findOneByName($data['title'], $categoryId);
        if (!empty($info)){
            Log::info(sprintf("标题为%s，分类ID%d为的文章已经存在", $data['title'], $categoryId));
            return true;
        }
        $data['original_title'] = $detailTool->getOriginalTitle($content);
        $data['source'] = $detailTool->getSource();
        $data['time'] = $detailTool->getTime();
        $data['description'] = $detailTool->getDesc();
        $data['content'] = $detailTool->getContent();
        $data['content_text'] = htmlspecialchars(static::$contentHtml);
        $data['category_id'] = $categoryId;
        return Content::insert($data);
    }

}