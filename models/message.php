<?php namespace Models;

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

use Application\Session;

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
	
	public static function save($message, $level = Message::LEVEL_ERROR) {
		$c = __CLASS__;
		$message = new $c($message, $level);
		
		Session::start();
		$_SESSION['messages'][] = $message;
		
		return $message;
	}
	
	public static function getSavedMessages() {
		if (isset($_SESSION['messages'])) {
			$messages = $_SESSION['messages'];
			unset($_SESSION['messages']);
			return $messages;
		} else {
			return array();	
		}
	}
	
	public function getLevel() {
		return $this->level;
	}
	
	public function getCSSLevel() {
		switch ($this->level) {
			case static::LEVEL_SUCCESS:
				return 'alert-success';
			case static::LEVEL_INFO:
				return 'alert-info';
			case static::LEVEL_WARNING:
				return 'alert-warning';
			case static::LEVEL_ERROR:
				return 'alert-danger';
		}
	}
	
	public function getMessage() {
		return $this->message;
	}
}

?>