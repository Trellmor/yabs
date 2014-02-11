<?php namespace DAL;

use Application\Registry;

class QueryBuilderException extends \Exception {
}

class Factory {
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
				return new Mysql();
			default:
				throw new QueryBuilderException('Invalid database driver: ' . $driver);
		}
	}
}

?>