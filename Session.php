<?php
/**
 * session 处理类
 */
namespace SimpleFr;

class Session
{
	/**
	 * 获取某个
	 */
	public function get($name)
	{
		return self::getValue($name);
	}

	/**
	 * 设置值
	 */
	public function set($name, $value)
	{
		self::setValue($name, $value);
	}

	/**
	 * 获取值
	 */
	static public function getValue($name)
	{
		return $_SESSION[$name];
	}

	/**
	 * 设置值
	 */
	static public function setValue($name, $value)
	{
		$_SESSION[$name] = $value;
	}
}
