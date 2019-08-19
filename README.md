# demo

```
├── Bootstrap
│   ├── Autoload.php
│   ├── Log.php
│   ├── SpiderIntoRedis.php
│   └── Tool.php
├── Config
│   ├── config.php
│   ├── Database.php
│   └── Redis.php
├── database.sql
├── Db
│   ├── Db.php
│   └── RedisOwn.php
├── Help
│   └── Help.php
├── index.php
├── Log
│   ├── default
│   ├── file
│   └── php-spider.log
├── Model
│   ├── Base.php
│   ├── Category.php
│   └── Content.php
├── multi-index.php
├── README.md
├── Service
│   ├── Base.php
│   ├── Detail.php
│   ├── Home.php
│   └── Main.php
└── Tool
    ├── Base.php
    └── DetailTool.php
```

1.  Bootstrap

    基础工具封装
2.  Config

    配置信息 包括不限于redis、database、基础配置
3.  Db

    mysql 和 redis 简单封装
4.  Log

    爬虫日志信息  
5.  Model

    模型类
6.  Service
    
    逻辑处理
7.  Tool
    
    处理详情页面方法所在，可定义多种规则
    
8.  index.php
    
    爬虫入口
9.  multi-index.php

    多进程爬虫入口
    
