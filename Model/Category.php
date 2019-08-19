<?php

namespace Model;
use Db\Db;
class Category extends Base
{
    protected static $tableName = "category";
    private static $filter = [
        'name', 'parentId', 'level'
    ];
    public static function insert($data)
    {
        try{
            $sql = sprintf("INSERT INTO %s (name, parent_id, level, create_at) VALUES (?, ?, ?, '%s')",
                static::$tableName,
                date("Y-m-d H:i:s"));
            $query= Db::connect()->prepare($sql);
            $query->execute(array_values($data));
//            var_dump(Db::connect()->errorInfo());
            return Db::connect()->lastInsertId();
        }catch (\PDOException $e){
            \Bootstrap\Log::error(sprintf("写入分类错误，数据为：%s，异常信息为：%s",
                json_encode($data),
                $e->getMessage())
            );
            return false;
        }
    }

    public static function findOneByName($name)
    {
        try{

            $sql = sprintf("select * from %s where name = '%s'",
                static::$tableName,
                $name);
            $query = Db::connect()->query($sql)->fetch();
            return $query;
        }catch (\PDOException $e){
            \Bootstrap\Log::error(sprintf("查询分类错误，异常信息为：%s",
                    $e->getMessage())
            );
            return false;
        }
    }
}