<?php
/**
 * 框架主文件
 * @athor kun
 */
namespace SimpleFr;

class SimpleFr
{
	/**
	 * 程序入口
	 * @param $config 配置信息
	 */
    static public function run($config)
    {
    	//判断是否定义, 默认application目录为应用目录
    	defined('APP_PATH') or define('APP_PATH', __DIR__.'/../application');

    	//定义自动加载
    	spl_autoload_register(['self', 'autoload']);
    	//解析路由
    	Route::parseUrl();

    	define('DB_HOST', $config['db']['host']);
    	define('DB_NAME', $config['db']['db_name']);
    	define('DB_USER', $config['db']['user']);
 		define('DB_PASSWORD', $config['db']['password']);
 		define('DB_PREFIX', $config['db']['db_prefix']);

    	session_start();
    }

    /**
     * 自动加载
     * @param 类名称
     */
    static public function autoload($className)
    {
    	$file = APP_PATH.str_replace('\\', '/', $className);
    
    	$fileArr = explode('/', $file);
    	if (!file_exists($file)) exit(end($file). '文件不存在');

    	if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') exit('非php文件');

    	require_once($file);
    }

}
