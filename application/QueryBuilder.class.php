<?php

interface QueryBuilder {
	function table($table);
	function where($selection, array $selectionArgs);
	function query(array $columns);
	function delete();
	function update(array $contentValues);
	function insert(array $contentValues);
}

class QueryBuilderException extends Exception {
}

class QueryBuilderBase {
	protected $table = NULL;
	protected $selection = "";
	protected $selectionArgs = array();
	
	public function where($selection, array $selectionArgs) {
		if (empty($selection)) {
			if ($selectionArgs != null && count($selectionArgs) > 0) {
				throw new QueryBuilderException('Valid selection required when including arguments');
			}
			
			return $this;
		}
		
		if (!empty($this->selection)) {
			$this->selection .= ' AND ';
		}
		$this->selection .= '( ' . $selection + ' )';
		$this->selectionArgs = array_merge($this->selectionArgs, $selectionArgs);
		
		return $this;
	}
	
	public function table($table) {
		$this->table = $table;
		return $this;
	}
	
	public function query(array $columns) {
		$query = 'SELECT ' . implode(', ', $columns) . ' FROM ' . Registry::getInstance()->db_prefix . $this->table;
		if (!empty($this->selection)) {
			$query .= ' WHERE ' . $this->selection;
		}
		$sth = Registry::getInstance()->db->prepare($query);
		$this->bindValues($sth, $this->selectionArgs);
		$sth->execute();
		return $sth;
	}
	
	public function delete() {
		
	}
	
	public function update(array $contentValues) {
		
	}
	
	public function insert(array $contentValues) {
		
	}
	
	protected function bindValues(PDOStatement $sth, array $values) {
		foreach ($values as $parameter => $value) {
			//Parameters are 1-based
			if (is_int($parameter)) {
				$parameter++;
			}
			
			if (is_array($value)) {
				$sth->bindValue($parameter, $value[0], $value[1]);
			} else {
				$sth->bindValue($parameter, $value);
			}
		}
	}
}

class QueryBuilderFactory {
	private function __construct() {
	}
	
	/**
	 * Create new QueryBuilder instance for the active database driver
	 * 
	 * @return QueryBuilder
	 * @throws QueryBuilderException
	 */
	public static function factory() {
		if (Registry::getInstance()->db == NULL) {
			throw new QueryBuilderException('DB not initialized');
		}
		
		$driver = Registry::getInstance()->db->getAttribute(PDO::ATTR_DRIVER_NAME);
		switch ($driver) {
			case 'mysql':
				require_once APP_ROOT . '/application/querybuilders/QueryBuilderMysql.class.php';
				return new QueryBuilderMysql();
			default:
				throw new QueryBuilderException('Invalid database driver: ' . $driver);
		}
	}
}


?>