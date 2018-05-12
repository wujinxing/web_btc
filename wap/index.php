<?php
    // 定义系统编码
    header("Content-Type: text/html;charset=utf-8");
    // 定义应用路径
    define('APP_PATH', './Application/');
    // 定义缓存路径
    define('RUNTIME_PATH', './Runtime/');
    // 定义备份路径
    define('DATABASE_PATH', './Database/');
    // 定义钱包路径
    define('COIN_PATH', './Coin/');
    // 定义备份路径
    define('UPLOAD_PATH', './Upload/');
    // 定义数据库类型
    define('DB_TYPE', 'mysql');
    // 定义数据库地址
    define('DB_HOST', '127.0.0.1');
    // 定义数据库名
    define('DB_NAME', 'web_btc');
    // 定义数据库账号
    define('DB_USER', 'root');
    // 定义数据库密码
    define('DB_PWD', 'root');
    // 定义数据库端口
    define('DB_PORT', '3306');
    // 开启演示模式
    define('APP_DEMO',0);
    // 开启手机验证码
    define('MOBILE_CODE',0);
    // 开始调试模式
    define('M_DEBUG', 1);
    // 定义电脑端网址
    define('PC_URL', 'http://111.231.110.195');
    // 后台安全入口
    define('ADMIN_KEY', 'qqbtc');
    define('DIR_SECURE_CONTENT', 'deny Access!');
    // 引入入口文件
    require './ThinkPHP/qqbtc.php';


