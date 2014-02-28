<?php namespace Models;

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