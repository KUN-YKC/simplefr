<?php
//配置文件
return [
	//数据库配置
	'db' => [
		'host' => 'localhost',
		'dbname' => 'test',
		'user' => 'user',
		'password' => 'password',
		'db_prefix' => ''
	],

	//默认控制器
	'default_controller' => 'index',
	//默认动作	
	'default_action' => 'index',

	//session处理
	'session_handle' => 'file',
	'session_save_path' => '/tmp',
];