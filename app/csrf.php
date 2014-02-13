<?php namespace Application; 

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