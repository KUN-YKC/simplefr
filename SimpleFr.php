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

    	define('DB_HOST', $config['db']['host']);
    	define('DB_NAME', $config['db']['db_name']);
    	define('DB_USER', $config['db']['user']);
 		define('DB_PASSWORD', $config['db']['password']);
 		define('DB_PREFIX', $config['db']['db_prefix']);
        define('DEFAULT_CONTROLLER', isset($config['default_controller']) ? $config['default_controller'] : 'index');
        define('DEFAULT_ACTION', isset($config['default_action']) ? $config['default_action'] : 'index');

    	session_start();

        //解析路由
        Route::parseUrl();
    }

    /**
     * 自动加载
     * @param 类名称
     */
    static public function autoload($className)
    {
    	$className = str_replace('\\', '/', $className);
    
    	if (strpos($className, 'SimpleFr') !== false) {
            $filePath = APP_PATH . '../'.strtolower($className).'.php';
        } else {
            if (strpos($className, '/') === false) $className = 'Controller/'.$className;

            $filePath = APP_PATH.$className.'.php';
        }
    	if (!file_exists($filePath)) exit($filePath. '文件不存在');

    	require_once($filePath);
    }

}
