<?php namespace Models;

use Application\Crypto\MD5;
use Application\Crypto\BCrypt;
use Application\Exceptions\ValidationException;
use DAL;

class User {
	const PERM_ENTRY_WRITE = 0;
	const PERM_ENTRY_ALL = 1;
	const PERM_SETTINGS = 2;		
	
	private $user_id = -1;
	private $user_mail;
	private $user_name;
	private $user_password;
	private $permissions = array();
	
	public function __construct() {
		if ($this->user_id > -1) {
			$this->permissions = DAL\Factory::newQueryBuilder()->
				table('yabs_user_permission')->
				where('user_id = ?', [[$this->user_id, \PDO::PARAM_INT]])->
				query(['user_permission'])->
				fetchAll(\PDO::FETCH_COLUMN, 0); 
		}
	}
	
	public static function verifyLogin($username, $password) {
		$sth = DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_name = ?', [$username])->query(['user_id', 'user_name', 'user_password']);
		if (($user = $sth->fetch()) !== false) {
			$hash = new BCrypt();
			if ($hash->verify($password, $user->user_password)) {
				if ($hash->needsRehash($user->user_password)) {
					static::updatePassword($user->user_id, $hash->hash($password));
				}
				return $user->user_id;
			} else {
				//TODO: Remove this once all users are updated
				$md5 = new MD5();
				if ($md5->verify($password, trim($user->user_password))) {
					static::updatePassword($user->user_id, $hash->hash($password));
					return $user->user_id;
				}
			}
		}
		return false;
	}
	
	protected static function updatePassword($userId, $hash) {
		$qb = DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_id = :user_id', ['user_id' => [$userId, \PDO::PARAM_INT]]);
		$qb->update(['user_password' => $hash]);
	}
	
	public static function load($userId) {
		$qb = DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_id = ?', [[$userId, \PDO::PARAM_INT]]);
		return $qb->query(['user_id', 'user_name', 'user_mail'])->fetchObject(__CLASS__);
	}
	
	public function hasPermission($permission) {
		return array_search($permission, $this->permissions) !== false;
	}
	
	public function getId() {
		return $this->user_id;
	}
	
	public function getName() {
		return $this->user_name;
	}
	
	public function setName($value) {
		if (empty($value)) {
			throw new ValidationException(_('Name is required.'));
		}
		$this->user_name = $value;
	}
	
	public function getMail() {
		return $this->user_mail;
	}
	
	public function setMail($value) {
		if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
			throw new ValidationException(_('Invalid E-Mail address.'));
		}
		$this->user_mail = $value;
	}
	
	public function setPassword($value) {
		$hash = new BCrypt();
		$this->user_password = $hash->hash($value);
	}
}

?>