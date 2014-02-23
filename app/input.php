<?php namespace Application;

class Input{
	const POST = 'POST';
	const GET = 'GET';
	
	private $data;
	
	public function __construct($method) {
		switch ($method) {
			case 'GET':
				parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $this->data);
				break;
			case 'POST':
				$this->data = $_POST;
				break;
			default:
				parse_str(file_get_contents('php://input'), $this->data);
				break;
		}
	}
	
	public function filter($variable, $filter, $options = null) {
		if ($options != null) {
			$this->data[$variable] = filter_var($this->{$variable}, $filter, $options);
		} else {
			$this->data[$variable] = filter_var($this->{$variable}, $filter);
		}
	}
	
	/**
	 * Set a variable
	 * 
	 * @param mixed $index
	 * @param mixed $value
	 */
	public function __set($index, $value) {
		if (isset($this->data[$index]) === false) {
			throw new \Exception('Invalid index: ' . $index);
		}
		$this->data[$index] = $value;
	}
	
	/**
	 * Get a variable
	 * 
	 * @param mixed $index
	 * @return mixed
	 */
	public function __get($index) {
		if (isset($this->data[$index])) {
			return $this->data[$index];
		} else {
			return null;
		}
	}
	
	public function __isset($index) {
		return isset($this->data[$index]);
	}
}

?>