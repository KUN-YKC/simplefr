<?php
/**
 * 默认controller文件
 */
namespace SimpleFr;

class Controller
{	
	public function __construct()
	{

	}

	/**
	 * 默认数据返回 
	 * @param $data 数据
	 * @param $dataType 'json' 数据格式, ‘’ 普通数据格式
	 */
	public function responseJson($data, $dataType = '')
	{
		header('Content-Type: apllication/json;charset=utf-8');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}

	public function display($fileName = '', $data = '')
	{
		$applicationPath = APP_PATH;

		$viewPath = $apllicationPath.'/view/';

		//获取子类的类名
		$childClassName = get_class($this);

		$methodList = get_class_methods($this);
		$filePath = $viewPath.$childClassName.'/'.($fileName ? $fileName : $methodList[0]).'.php';

		if (!file_exists($filePath)) exit("文件找不到!!!");

		$data && extract($data);

		require_once $filePath;
	}
}