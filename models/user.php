<?php namespace Models;

use Application\Registry;
use Application\Crypto\MD5;
use Application\Crypto\BCrypt;
use Application\Exceptions\ValidationException;
use DAL;

class User {
	const PERM_ENTRY = 0;
	const PERM_ENTRY_ALL = 1;
	const PERM_SETTINGS = 2;
	const PERM_CATEGORY = 3;
	const PERM_COMMENT = 4;
	const PERM_USER = 5;
	
	private $user_id = -1;
	private $user_mail;
	private $user_name;
	private $user_password = null;
	private $user_active;
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
	
	/**
	 * Verify username and password
	 * 
	 * @param string $username
	 * @param string $password
	 * @return User object on success or false on failure
	 */
	public static function verifyLogin($username, $password) {
		$sth = DAL\Factory::newQueryBuilder()->table('yabs_user')->
			where('user_name = ? and user_active = ?', [$username, [1, \PDO::PARAM_INT]])->
			query(['user_id', 'user_name', 'user_mail', 'user_password', 'user_active']);
		
		if (($user = $sth->fetchObject(__CLASS__)) !== false) {
			$hash = new BCrypt();
			if ($hash->verify($password, $user->user_password)) {
				if ($hash->needsRehash($user->user_password)) {
					$user->setPassword($password);
					$user->save();
				}
				return $user;
			} else {
				//TODO: Remove this once all users are updated
				$md5 = new MD5();
				if ($md5->verify($password, trim($user->user_password))) {
					$user->setPassword($password);
					$user->save();
					return $user;
				}
			}
		}
		return false;
	}
	
	public static function load($userId, $onlyActive = false) {
		$qb = DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_id = ?', [[$userId, \PDO::PARAM_INT]]);
		if ($onlyActive) {
			$qb->where('user_active = ?', [[1, \PDO::PARAM_INT]]);
		} 
		return $qb->query(['user_id', 'user_name', 'user_mail', 'user_active'])->fetchObject(__CLASS__);
	}
	
	public static function getUsers() {
		return $qb = DAL\Factory::newQueryBuilder()->table('yabs_user')->orderBy(['user_name ASC'])->
			query(['user_id', 'user_name', 'user_mail', 'user_active'])->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}
	
	public function save() {
		if ($this->user_id < 0) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	public function update() {
		$values = 	[
						'user_mail' => $this->user_mail,
						'user_active' => $this->user_active
					];
					
		if ($this->user_password != null) {
			$values['user_password'] = $this->user_password;
		}
		
		DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_id = :user_id', ['user_id' => [$this->user_id, \PDO::PARAM_INT]])->
			update($values);
	}
	
	public function delete() {
		Registry::getInstance()->db->beginTransaction();
		try {
			DAL\Factory::newQueryBuilder()->table('yabs_entry')->where('user_id = :userId', ['userId' => [$this->user_id, \PDO::PARAM_INT]])->
				update(['user_id' => [null, \PDO::PARAM_INT]]);
			DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_id = ?', [[$this->user_id, \PDO::PARAM_INT]])->delete();
			
			Registry::getInstance()->db->commit();
		} catch(\PDOException $e) {
			Registry::getInstance()->db->rollBack();
			throw $e;
		}
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
		if (empty($value)) {
			throw new ValidationException(_('Invalid password.'));
		}
		
		$hash = new BCrypt();
		$this->user_password = $hash->hash($value);
	}
	
	public function isActive() {
		return (bool) $this->user_active;
	}
	
	public function setActive($value) {
		$this->user_active = (int) $value;
	}
}

?>