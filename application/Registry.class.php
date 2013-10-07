<?php

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
	 * @param mixed $index
	 * @param mixed $value
	 */
	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}
	
	/**
	 * Get a variable
	 * 
	 * @param mixed $index
	 * @return mixed
	 */
	public function __get($index) {
		return $this->vars[$index];
	}
}

?>