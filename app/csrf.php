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

use Application\Crypto;
use Application\Crypto\SecureRandom;

class CSRF {
	private static $name = 'csrf_token';
	
	public function getToken() {
		//Start session
		Session::start();
		
		if (!empty($_SESSION[static::$name])) {
			return $_SESSION[static::$name];
		} else {			
			$token = $this->generateToken();
			$_SESSION[static::$name] = $token;
			return $token;
		}
	}
	
	public function getName() {
		return static::$name;
	}
	
	public function verifyToken() {
		if (empty($_SESSION[static::$name])) {
			return false;
		}
		
		$input = new Input('POST');
		if (empty($input->csrf_token)) {
			return false;
		}
			
		return Crypto\Utils::compareStr($_SESSION[static::$name], $input->csrf_token);
	}
	
	protected function generateToken() {
		$sr = new SecureRandom();
		return base64_encode($sr->getBytes(32)); //256 bit token
	}
}

?>