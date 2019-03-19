<?php
/**
 * 路由解析
 * @author kun
 */
namespace SimpleFr;

class Route
{
    /**
     * 解析路由
     */
    static public function parseUrl()
    {
        $requestUri = getenv('REQUEST_URI');

       	$scriptName = getenv('SCRIPT_NAME');

       	
       	$scriptNameArr = explode('/', $scriptName);

       	if (count($scriptNameArr) > 2) {
       		$requestUri = rtrim($requestUri, '/');

       		$startIndex = 1;
       		foreach ($scriptNameArr as $key => $val) {
       			if ($key + 1 == count($scriptNameArr)) break;

       			$startIndex += strlen($val);
       		}
       		$requestUri = substr($requestUri, $startIndex);
       	}

       	if (strpos($requestUri, $scriptName) !== false) {
       		$requestUri = str_replace($scriptName, '', $requestUri);
       	}

       	//默认控制器方法
       	$actionName = strtolower(DEFAULT_ACTION);

       	//默认加载默认控制器,默认
       	if ($requestUri == '/') {
       		$controllerName = ucfirst(DEFAULT_CONTROLLER);
       	} else {
       		$requestUri = rtrim($requestUri, '/');
       		$requestUri = explode('/', $requestUri);

       		$controllerName = ucfirst($requestUri[0]);
       		if (count($requestUri) > 1) {
       			$actionName = $requestUri[1];
       		} 
       	}
       	$filePath = APP_PATH.'/Controller/'.$controllerName.'.php';


       	if (!file_exists($filePath)) exit('控制器不存在');

       	$controllerName = 'Controller\\'.$controllerName;

       	if (!method_exists($controllerName, $actionName)) exit('方法不存在');

       	(new $controllerName)->$actionName();
    }
}
