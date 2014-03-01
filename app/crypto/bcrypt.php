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

class BCrypt extends Hash {
	private $rounds = 12;
		
	public function __construct($rounds = 12) {
		$this->rounds = 12;
	}
	
	public function getRounds() {
		return $this->rounds;
	}
	
	public function setRounds($rounds) {
		$this->rounds = $rounds;
	}
	
	protected function getSaltLength() {
		return 16;
	}
	
	protected function getResultLength() {
		return 60;
	}
	
	protected function getIdentifier() {
		return sprintf("$2a$%02d$", $this->rounds);
	}
}

?>