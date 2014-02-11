<?php namespace Models;

class Message {
	const LEVEL_ERROR = 'error';
	const LEVEL_INFO = 'info';
	const LEVEL_WARNING = 'warning';
	const LEVEL_SUCCESS = 'success';
	
	private $level;
	private $message;
	
	public function __construct($message, $level = Message::LEVEL_ERROR) {
		$this->message = $message;
		$this->level = $level;
	}
	
	public function getLevel() {
		return $this->level;
	}
	
	public function getMessage() {
		return $this->message;
	}
}

?>