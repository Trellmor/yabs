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

use Application\Exceptions\RNGException;

class SecureRandom {
	public function getBytes($count) {
		$bytes = '';
		if (function_exists('openssl_random_pseudo_bytes') &&
				(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) {
			// Primary source for random bytes is the OpenSSL prng,
			// but OpenSSL is slow on windows, so avoid it there
			$bytes = openssl_random_pseudo_bytes($count);
		} else if (function_exists('mcrypt_create_iv')) {
			// Use mcrypt_create_iv to read bytes from /dev/urandom
			$bytes = mcrypt_create_iv($count, MCRYPT_DEV_URANDOM);
		} else if (is_readable('/dev/urandom') &&
				($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
			// Read from /dev/urandom directly if available
			$bytes = fread($hRand, $count);
			fclose($hRand);
		}
		
		if ($bytes === false || Utils::binaryStrlen($bytes) < $count) {
			throw new RNGException('Failed to get random bytes.');
		}		
		
		return $bytes;
	}
}

?>