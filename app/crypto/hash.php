<?php namespace Application\Crypto;

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

use Application\Exceptions\HashException;

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
		return Utils::compareStr($existingHash, $hash);
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
	
		if (Utils::binaryStrlen($hash) != $this->getResultLength()) {
			throw new HashException("Error while generating hash");
		}
	
		return $hash;
	}
	
	public function needsRehash($oldHash) {
		$ident = $this->getIdentifier();
		
		if (Utils::binaryStrlen($ident) > Utils::binaryStrlen($oldHash)) {
			return true;
		}
		
		$oldIdent = Utils::binarySubstr($oldHash, 0, Utils::binaryStrlen($ident));
		return $ident !== $oldIdent;
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
	
	private function getRandomBytes($count) {
		$sr = new SecureRandom();
		return $sr->getBytes($count);
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