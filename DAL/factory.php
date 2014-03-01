<?php namespace DAL;

/**
 * yabs -  Yet another blog system
 * Copyright (C) 2014 Daniel Triendl <daniel@pew.cc>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use Application\Registry;
use Application\Exceptions\DALException;

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
			throw new DALException('DB not initialized');
		}

		$driver = Registry::getInstance()->db->getAttribute(\PDO::ATTR_DRIVER_NAME);
		switch ($driver) {
			case 'mysql':
				return new QueryBuilder\MySQL();
			default:
				throw new DALException('Invalid database driver: ' . $driver);
		}
	}
	
	public static function DAL() {
		if (isset(static::$DAL)) {
			return static::$DAL;
		}
		
		if (Registry::getInstance()->db == NULL) {
			throw new DALException('DB not initialized');
		}
		
		$driver = Registry::getInstance()->db->getAttribute(\PDO::ATTR_DRIVER_NAME);
		switch ($driver) {
			case 'mysql':
				static::$DAL = new MySQL();
				return static::$DAL;
			default:
				throw new DALException('Invalid database driver: ' . $driver);
		}
	}
}

?>