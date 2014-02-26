<?php namespace DAL;

use Application\Registry;
use Application\Exceptions\QueryBuilderException;

class Factory {
	private static $DAL = null;
	
	private function __construct() {
	}

	/**
	 * Create new QueryBuilder instance for the active database driver
	 *
	 * @return IQueryBuilder
	 * @throws QueryBuilderException
	 */
	public static function newQueryBuilder() {
		if (Registry::getInstance()->db == NULL) {
			throw new QueryBuilderException('DB not initialized');
		}

		$driver = Registry::getInstance()->db->getAttribute(\PDO::ATTR_DRIVER_NAME);
		switch ($driver) {
			case 'mysql':
				return new QueryBuilder\MySQL();
			default:
				throw new QueryBuilderException('Invalid database driver: ' . $driver);
		}
	}
	
	public static function DAL() {
		if (isset(static::$DAL)) {
			return static::$DAL;
		}
		
		if (Registry::getInstance()->db == NULL) {
			throw new QueryBuilderException('DB not initialized');
		}
		
		$driver = Registry::getInstance()->db->getAttribute(\PDO::ATTR_DRIVER_NAME);
		switch ($driver) {
			case 'mysql':
				static::$DAL = new MySQL();
				return static::$DAL;
			default:
				throw new QueryBuilderException('Invalid database driver: ' . $driver);
		}
	}
}

?>