<?php namespace Application\hash;

class HashException extends \Exception {}

abstract class Hash {
	/**
	 * Verify the input against an existing hash
	 * 
	 * The function will determine the hash algorithm and parameters from the 
	 * $existingHash.
	 * 
	 * @param sting $input
	 * @param string $existingHash
	 * 
	 * @return True if the input matches the existing hash
	 */
	public function verify($input, $existingHash) {
		$hash = crypt($input, $existingHash);
		return static::compareStr($existingHash, $hash);
	}

	/**
	 * Generate the hash of an input
	 * 
	 * @param string$input
	 * 
	 * @return The hashed input
	 */
	public function hash($input) {
		$hash = crypt($input, $this->getSalt());
	
		if (static::binaryStrlen($hash) != $this->getResultLength()) {
			throw new HashException("Error while generating hash");
		}
	
		return $hash;
	}
	
	public function needsRehash($oldHash) {
		$ident = $this->getIdentifier();
		
		if (static::binaryStrlen($ident) > static::binaryStrlen($oldHash)) {
			return true;
		}
		
		$oldIdent = static::binarySubstr($oldHash, 0, static::binaryStrlen($ident));
		return $ident !== $oldIdent;
	}
	
	/**
	 * Constant time string comparison
	 *  
	 * @param string $str1
	 * @param string $str2
	 * 
	 * @return True if strings are equal
	 */
	public static function compareStr($str1, $str2) {
		$len1 = static::binaryStrlen($str1);
		$len2 = static::binaryStrlen($str2);
		$len = min($len1, $len2);
		$diff = $len1 ^ $len2;
		
		for ($i = 0; $i < $len; $i++) {
			$diff |= ord($str1[$i]) ^ ord($str2[$i]);
		}
		
		return $diff === 0;
	}
	
	/**
	 * Count the number of bytes in a string
	 * 
	 * mbstring extension may overwrite strlen and return a strings character
	 * count instead of the number of bytes. 
	 * 
	 * @param string $str
	 * 
	 * @return int The number of bytes
	 */
	public static function binaryStrlen($str) {
		if (function_exists('mb_Strlen')) {
			return mb_strlen($str, '8bit');
		}
		return strlen($str);
	}
	
	/**
	 * Get a substring based on byte limits
	 * 
	 * @see binaryStrlen
	 * 
	 * @param string $str Input string
	 * @param int $start
	 * @param int $length
	 * 
	 * @return string The substring
	 */
	public static function binarySubstr($str, $start, $length) {
		if (function_exists('mb_substr')) {
			return mb_substr($str, $start, $length, '8bit');
		}
		
		return substr($string, $start, $length);
	}
	
	protected abstract function getIdentifier();
	protected abstract function getSaltLength();
	protected abstract function getResultLength(); 
	
	//http://stackoverflow.com/a/6337021/833893
	private function getSalt() {
		$salt = $this->getIdentifier();
	
		$bytes = $this->getRandomBytes($this->getSaltLength());
	
		$salt .= $this->encodeBytes($bytes);
	
		return $salt;
	}	
	
	private $randomState;
	private function getRandomBytes($count) {
		$bytes = '';
	
		if(function_exists('openssl_random_pseudo_bytes') &&
				(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL slow on Win
			$bytes = openssl_random_pseudo_bytes($count);
		}
	
		if($bytes === '' && is_readable('/dev/urandom') &&
				($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
			$bytes = fread($hRand, $count);
			fclose($hRand);
		}
	
		if(strlen($bytes) < $count) {
			$bytes = '';
	
			if($this->randomState === null) {
				$this->randomState = microtime();
				if(function_exists('getmypid')) {
					$this->randomState .= getmypid();
				}
			}
	
			for($i = 0; $i < $count; $i += 16) {
				$this->randomState = md5(microtime() . $this->randomState);
	
				if (PHP_VERSION >= '5') {
					$bytes .= md5($this->randomState, true);
				} else {
					$bytes .= pack('H*', md5($this->randomState));
				}
			}
	
			$bytes = substr($bytes, 0, $count);
		}
	
		return $bytes;
	}
	
	private function encodeBytes($input) {
		// The following is code from the PHP Password Hashing Framework
		$itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
		$output = '';
		$i = 0;
		do {
			$c1 = ord($input[$i++]);
			$output .= $itoa64[$c1 >> 2];
			$c1 = ($c1 & 0x03) << 4;
			if ($i >= 16) {
				$output .= $itoa64[$c1];
				break;
			}
	
			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 4;
			$output .= $itoa64[$c1];
			$c1 = ($c2 & 0x0f) << 2;
	
			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 6;
			$output .= $itoa64[$c1];
			$output .= $itoa64[$c2 & 0x3f];
		} while (1);
	
		return $output;
	}
}

?>