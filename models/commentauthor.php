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

class CommentAuthor {
	private $name = "";
	private $mail = "";
	private $url = "";
	private $remember = false;
	
	public function __construct() {
		if (isset($_COOKIE['yabs_commentauthor'])) {
			$data = json_decode($_COOKIE['yabs_commentauthor'], true);
			if ($data !== null) {
				if (isset($data['name'])) {
					$this->name = $data['name'];
				}
				if (isset($data['mail'])) {
					$this->mail = $data['mail'];
				}
				if (isset($data['url'])) {
					$this->url = $data['url'];
				}
				$this->remember = true;
			}
		}
	}
	
	public function save() {
		if ($this->remember) {			
			setcookie(
					'yabs_commentauthor',
					json_encode([
							'name' => $this->name,
							'mail' => $this->mail,
							'url' => $this->url,
							]),
					time() + 60 * 60 * 24 * 365,
					'/'
			);
		} else {
			if (isset($_COOKIE['yabs_commentauthor'])) {
				unset($_COOKIE['yabs_commentauthor']);
				setcookie(
						'yabs_commentauthor',
						'',
						time() - 42000,
						'/'
				);
			}
		}
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($value) {
		$this->name = $value;
	}
	
	public function getMail() {
		return $this->mail;
	}
	
	public function setMail($value) {
		$this->mail = $value;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function setUrl($value) {
		$this->url = $value;
	}
	
	public function isRemember() {
		return $this->remember;
	}
	
	public function setRemember($value) {
		$this->remember = (bool) $value;
	}
}

?>