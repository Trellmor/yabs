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

class MD5 extends Hash {
	
	public function __construct() {
	}

	public function verify($input, $existingHash) {
		//If we have a 32char string w/o a $ at the beginning, it's probably a unsalted md5 hash 
		if (strlen($existingHash) == 32) {
			if ($existingHash[0] !== '$') {
				return Utils::compareStr(md5($input), $existingHash);
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