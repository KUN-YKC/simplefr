<?php
namespace SimpleFr;

class Model
{
	private $conn;

	protected $tableName;

	//条件
	private $condition;
	//字段返回
	private $fields = '*';

	private $orderBy;

	private $groupBy;

	private $limit;

	private $joinStr;

	private $alias;

	/**
	 * 构造
	 */
	public function __construct()
	{
		$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
		$this->conn = new \PDO('mysql:host='.HOST.';DBNAME='.DB_NAME, DB_USER, DB_PASSWORD, $options);
	
		if ($this->conn->errorCode()) {
			exit($this->conn->errorInfo()[2]);
		}

		$tablePrefix = defined('DB_PREFIX') ? DB_PREFIX : '';

		//表的名称是否有定义,如果没有,解析类的名称即可
		if (!$this->tableName) {
			$className = explode('\\', __CLASS__);

			$className = end($className);

			$this->tableName = $tablePrefix.$this->parseClassNameToTableName($className);
		} else {
			$this->tableName = $tablePrefix.$this->tableName;
		}

	}

	/**
	 *
	 * 解析类名转变为table
	 */
	private function parseClassNameToTableName($className)
	{
		$index = 1;
		$data = [];
		while($index) {
			$classNameLen = strlen($className);
			if ($index ==  $classNameLen || $classNameLen == 1) break;

			$ordNumber = ord($className{$index});

			if ($ordNumber >= 65 && $ordNumber <= 90) {
				$data[] = substr($className, 0, $index);
				$className = substr($className, $index);
				$index = 1;
			}

			$index++;
		}
		strlen($className) && $data[] = $className;

		return implode('_', $data);
	}

	/**
	 *	获取单一数据
	 */
	public function find()
	{
		$this->limit(0, 1);
		$sql = $this->setSelectSql;
	
		$stmt = $this->conn->prepare($sql);

		return $stmt->fetch(PDO::FETCH_ARRAY);
	}

 	/**
 	 * 获取所有数据
 	 */
	public function findAll()
	{
		$stmt = $this->conn->prepare($this->setSelectSql);

		return $stmt->fetchAll(PDO::FETCH_ARRAY);
	}

	/**
	 * 查询语句
	 */
	private function setSelectSql()
	{	
		return 'SELECT '.$this->fields.' FROM '.$this->tableName.' '.$this->alias.$this->joinStr.$this->condition.$this->orderBy.$this->groupBy.$this->limit;
	}

	/**
	 * 删除数据
	 */
	public function delete()
	{
		$stmt = $this->conn->prepare('DELETE '.$this->tableName.' '.$this->condition);

		$stmt->execte();

		return $stmt->rowCount();
	}

	/**
	 * 添加数据
	 */
	public function add($data = [])
	{
		$insertSql = 'INSERT INTO '.$this->tableName.' (';

		$insertValue = array_values($data);

		$insertSql .= implode(',', array_keys($data)).')';

		$insertSql .= ' VALUES ('.rtrim(str_repeat('?,', count($insertValue)), ',').')';

		$stmt = $this->conn->prepare($insertSql);

		$stmt->execute($insertValue);

		return $stmt->rawCount();
	}

	/**
	 * 添加所有
	 */
	public function addAll($data = [])
	{
		$firstsData = $data[0];

		$keys = array_keys($firstsData);

		$insertSql = 'INSERT INTO '.$this->tableName.' ('.implode(',', $keys).')';

		$insertSql .= ' VALUES ';

		foreach ($data as $val) {
			$insertSql .= '(';
			foreach ($val as $v) {
				$insertSql .= '"'.$v.'",'
			}
			$insertSql = rtrim($insertSql, ',');
			$insertSql .= '),';
		}
		$insertSql  = rtrim($insertSql, ',');

		return	$this->conn->exec($insertSql);
	}

	/**
	 *	保存数据
	 */
	public function save($data)
	{
		if (!$this->condition) {
			return $this->add($data);
		} else {
			$updateSql = 'UPDATE '.$this->tableName.' ';
			foreach ($data as $key => $val) {
				$updateSql .= $key.'="'.$val.'",';
			}
			$updateSql = rtrim($updateSql, ',');
			$updateSql .= $this->condition;

			return $this->conn->exec($updateSql);
		}
	}

	/**
	 * 获取最近写入的id
	 */
	public function getLastInsertId($field = 'id')
	{
		return $this->conn->lastInsertId($field);
	}

	/**
	 * 别名
	 */
	public function alias($aliasName)
	{
		$this->alias = $aliasName;

		return $this;
	}

	/**
	 * 条件定义
	 * @param $condition 条件
	 */
	public function where($condition)
	{
		if (is_array($condition)) {
			foreach ($condition as $key => $val) {
				$this->condition .= $key .'="'.$val.'" AND ';
			}
		} else {
			$this->condition .= ' AND '.$condition;
		}

		$this->condition  = ' WHERE '. trim($this->condition, 'AND ');

		return $this;
	}

	/**
	 * 多表关联查询
	 *
	 * @param $table 关联表
	 * @param $joinType 关联的方式 left right inner
	 * @param $on 关联条件
	 */
	public function join($table, $joinType = 'left', $on)
	{
		switch ($joinType) {
			case 'left':
				$this->joinStr .= ' LEFT JOIN '.$table.' ON '.$on;
				break;
			case 'right':
				$this->joinStr .= ' RIGHT JOIN '.$table.' ON '.$on;
				break;
			case 'inner':
				$this->joinStr .= ' INNER JOIN '.$table.' ON '.$on
				break;
		}

		return $this;
	}

	/**
	 * 返回字段
	 * @fields 
	 */
	public function feild($fileds = '*')
	{
		$this->fields = $fields;
		return $this;
	}

	/**
	 * 排序
	 */
	public function orderBy($orderBy)
	{
		$this->orderBy = 'ORDER BY '.$orderBy;

		return $this;
	}

	/**
	 * 分组
	 */
	public function groupBy($groupBy)
	{
		$this->groupBy = ' GROUP BY '.$groupBy;

		return $this;
	}

	/**
	 * 限制返回数量
	 */
	public function limit($offset = 0, $limit = 1)
	{
		$this->limit = ' LIMIT '.$offset.','.$limit;

		return $this;
	}
}