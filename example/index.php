<?php
//应用入口文件

define('APP_PATH', str_replace('\\', '/', __DIR__).'/app/');

//配置文件
$config = require_once './config.php';

require_once '../SimpleFr.php';

SimpleFr\SimpleFr::run($config);