<?php namespace Application\Crypto;

class MD5 extends Hash {
	
	public function __construct() {
	}

	public function verify($input, $existingHash) {
		//If we have a 32char string w/o a $ at the beginning, it's probably a unsalted md5 hash 
		if (strlen($existingHash) == 32) {
			if ($existingHash[0] !== '$') {
				return static::compareStr(md5($input), $existingHash);
			}
		}
		
		return parent::verify($input, $existingHash);
	}
	
	protected function getSaltLength() {
		return 12;
	}
	
	protected function getResultLength() {
		return 48;
	}
	
	protected function getIdentifier() {
		return '$1$';
	}
}

?>