<?php

namespace Model;
use Db\Db;
class Content
{
    protected static $tableName = "content";
    private static $filter = [
        'title',
        'original_title',
        'source',
        'time',
        'description',
        'content',
        'content_text',
        'category_id',
        'create_at'
    ];
    public static function insert($data)
    {
        try{
            $sql = sprintf("INSERT INTO %s (title, original_title, source, time, description, content, content_text, category_id, create_at)
 VALUES (:title, :original_title, :source, :time, :description, :content, :content_text, :category_id, '%s')",
                static::$tableName,
                date("Y-m-d H:i:s"));
            $query = Db::connect()->prepare($sql);
            $query->execute($data);
//            var_dump(Db::connect()->errorInfo());
//            $query->debugDumpParams();
            return Db::connect()->lastInsertId();
        }catch (\PDOException $e){
            \Bootstrap\Log::error(sprintf("写入文章错误，数据为：%s，异常信息为：%s",
                    json_encode($data),
                    $e->getMessage())
            );
            return false;
        }
    }

    public static function findOneByName($title, $categoryId)
    {
        try{
            $sql = sprintf("select * from %s where title = %s and category_id = %d",
                static::$tableName,
                $title,
                $categoryId);
            $query = Db::connect()->query($sql);

            return $query;
        }catch (\PDOException $e){
            \Bootstrap\Log::error(sprintf("查询文章错误，异常信息为：%s",
                    $e->getMessage())
            );
            return false;
        }
    }
}