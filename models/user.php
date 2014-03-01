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

use Application\Registry;
use Application\Crypto\MD5;
use Application\Crypto\BCrypt;
use Application\Crypto\SecureRandom;
use Application\Crypto\Utils as CryptoUtils;
use Application\Exceptions\ValidationException;
use DAL;

class User {
	const PERM_ENTRY = 0;
	const PERM_ENTRY_ALL = 1;
	const PERM_SETTINGS = 2;
	const PERM_CATEGORY = 3;
	const PERM_COMMENT = 4;
	const PERM_USER = 5;
	const PERM_IMAGE = 6;
	const PERM_IMAGE_EDIT = 6;
	
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
			if ($user->checkPassword($password)) {
				return $user;
			}
		}
		return false;
	}
	
	public static function load($userId, $onlyActive = false) {
		$qb = DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_id = ?', [[$userId, \PDO::PARAM_INT]]);
		if ($onlyActive) {
			$qb->where('user_active = ?', [[1, \PDO::PARAM_INT]]);
		} 
		return $qb->query(['user_id', 'user_name', 'user_mail', 'user_active', 'user_password'])->fetchObject(__CLASS__);
	}
	
	/**
	 * Verify remember login token
	 * 
	 * The token will be invalidated if the login succeeds
	 * 
	 * @param string $username
	 * @param string $password
	 * @return User object on success or false on failure
	 */
	public static function verifyRememberToken($userId, $userRememberToken) {
		$tokens = DAL\Factory::newQueryBuilder()->table('yabs_user_remember')->
			where('user_id = ? and user_remember_date > ?', [[$userId, \PDO::PARAM_INT], [time() - 60 * 60 * 24 * 30, \PDO::PARAM_INT]])->
			query(['user_remember_token'])->fetchAll(\PDO::FETCH_COLUMN, 0);
		if ($tokens !== false) {
			foreach ($tokens as $token) {
				if (CryptoUtils::compareStr($userRememberToken, $token)) {
					
					DAL\Factory::newQueryBuilder()->table('yabs_user_remember')->
						where('user_remember_token = ? or user_remember_date < ?', [$token, [time() - 60 * 60 * 24 * 30, \PDO::PARAM_INT]])->
						delete();
					
					return User::load($userId, true);
				}
			}
		}
		return false;
	}
	
	public static function getUsers() {
		return $qb = DAL\Factory::newQueryBuilder()->table('yabs_user')->orderBy(['user_name ASC'])->
			query(['user_id', 'user_name', 'user_mail', 'user_active', 'user_password'])->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}
	
	public function save() { 
		if ($this->user_id < 0) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	private function insert() {
		Registry::getInstance()->db->beginTransaction();
		try {
			$this->user_id = DAL\Factory::newQueryBuilder()->table('yabs_user')->insert([
					'user_name' => $this->user_name,
					'user_mail' => $this->user_mail,
					'user_active' => $this->user_active,
					'user_password' => $this->user_password
			]);
			
			$this->savePermissions();		
			
			Registry::getInstance()->db->commit();
		} catch (\PDOException $e) {
			Registry::getInstance()->db->rollback();
			throw $e;
		}
	}
	
	private function update() {
		Registry::getInstance()->db->beginTransaction();
		try {
			DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_id = :user_id', ['user_id' => [$this->user_id, \PDO::PARAM_INT]])->
				update([
					'user_name' => $this->user_name,
					'user_mail' => $this->user_mail,
					'user_active' => $this->user_active,
					'user_password' => $this->user_password
				]);
			
			
			$this->savePermissions();		
			
			Registry::getInstance()->db->commit();
		} catch (\PDOException $e) {
			Registry::getInstance()->db->rollback();
			throw $e;
		}
	}
	
	private function savePermissions() {
		DAL\Factory::newQueryBuilder()->table('yabs_user_permission')->where('user_id = ?', [[$this->user_id, \PDO::PARAM_INT]])->delete();
		
		$sth = DAL\Factory::newQueryBuilder()->table('yabs_user_permission')->generateInsert(['user_id', 'user_permission']);
		$sth->bindParam(':user_id', $this->user_id, \PDO::PARAM_INT);
		$permission = null;
		$sth->bindParam(':user_permission', $permission, \PDO::PARAM_INT);
		
		foreach ($this->permissions as $permission) {
			$sth->execute();
		}
	} 
	
	public function delete() {
		Registry::getInstance()->db->beginTransaction();
		try {
			DAL\Factory::newQueryBuilder()->table('yabs_entry')->where('user_id = :userId', ['userId' => [$this->user_id, \PDO::PARAM_INT]])->
				update(['user_id' => [null, \PDO::PARAM_INT]]);
			DAL\Factory::newQueryBuilder()->table('yabs_user_permission')->where('user_id = ?', [[$this->user_id, \PDO::PARAM_INT]])->delete();
			$this->clearRememberTokens();
			DAL\Factory::newQueryBuilder()->table('yabs_user')->where('user_id = ?', [[$this->user_id, \PDO::PARAM_INT]])->delete();
			
			Registry::getInstance()->db->commit();
		} catch(\PDOException $e) {
			Registry::getInstance()->db->rollBack();
			throw $e;
		}
	}
	
	public function clearRememberTokens() {
		DAL\Factory::newQueryBuilder()->table('yabs_user_remember')->where('user_id = ?', [[$this->user_id, \PDO::PARAM_INT]])->delete();
	}
	
	public function generateRememberToken() {
		$sr = new SecureRandom();
		$token = $sr->getBytes(32); //256 bit token
		DAL\Factory::newQueryBuilder()->table('yabs_user_remember')->insert([
				'user_id' => [$this->user_id, \PDO::PARAM_INT],
				'user_remember_token' => $token,
				'user_remember_date' => time()
			]);
		return $token;
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
		
		if (DAL\Factory::newQueryBuilder()->table('yabs_user')->
				where('user_name = ? and user_id != ?', [$value, [$this->user_id, \PDO::PARAM_INT]])->
				query(['count(*)'])->fetchColumn(0) > 0) {
			throw new ValidationException(_('There is already an user with this name.'));
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
	
	/**
	 * Set the users password
	 * 
	 * The password will be hashed
	 * All remember login tokens will be expired
	 * 
	 * @param string $value
	 * @throws ValidationException
	 */
	public function setPassword($value) {
		if (empty($value)) {
			throw new ValidationException(_('Invalid password.'));
		}
		
		$hash = new BCrypt();
		$this->user_password = $hash->hash($value);
		
		//Invalidate remember login tokens
		$this->clearRememberTokens();
	}
	
	public function checkPassword($password) {		
		$hash = new BCrypt();
		
		if ($hash->verify($password, $this->user_password)) {
			if ($hash->needsRehash($this->user_password)) {
				$this->setPassword($password);
				$this->save();
			}
			return true;
		} else {
			//TODO: Remove this once all users are updated
			$md5 = new MD5();
			if ($md5->verify($password, trim($this->user_password))) {
				$this->setPassword($password);
				$this->save();
				return true;
			}
		}
		
		return false;
	}
	
	public function isActive() {
		return (bool) $this->user_active;
	}
	
	public function setActive($value) {
		$this->user_active = (int) $value;
	}
	
	public function hasPermission($permission) {
		return array_search($permission, $this->permissions) !== false;
	}
	
	public function setPermission($permission, $value) {
		if ($value) {
			if (!$this->hasPermission($permission)) {
				$this->permissions[] = $permission;
			}
		} else {
			if (($index = array_search($permission, $this->permissions)) !== false) {
				unset($this->permissions[$index]);
			}
		}
	}
}

?>