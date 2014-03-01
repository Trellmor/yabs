<?php namespace Application;

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

class Registry {
	private $vars = array();
	private static $instance = NULL;
	
	private function __construct() {
	}
	
	public function __destruct() {
		self::$instance = NULL;
	}
	
	/**
	 * Get global registry instance
	 * 
	 * @return Registry instance
	 */
	public static function getInstance() {
		if (self::$instance == NULL) {
			$class = __CLASS__;
			self::$instance = new $class;
		}
		
		return self::$instance;
	}
	
	/**
	 * Set a variable
	 * 
	 * @param string $index
	 * @param mixed $value
	 */
	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}
	
	/**
	 * Get a variable
	 * 
	 * @param string $index
	 * @return mixed
	 */
	public function __get($index) {
		return $this->vars[$index];
	}
	
	/**
	 * Check if a variable is set
	 * 
	 * @param string $index
	 * @return True if the variable is set
	 */
	public function __isset($index) {
		return isset($this->vars[$index]);
	}
}

?>