<?php namespace Controllers\Admin;

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
use Application\Input;
use Application\Uri;
use Application\Exceptions\ValidationException;
use Models;
use Models\Message;

class User extends AdminController {
	function profile() {
		$this->view->load('profile');
	}
	
	function saveProfile() {	
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Save failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/profile'));
			exit;
		}
		
		$input = new Input(Input::POST);
		$input->filter('user_password_change', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_mail', FILTER_SANITIZE_EMAIL);
		
		$user = Models\User::load(Registry::getInstance()->user->getId());
		if ($user !== false) {
			try {
				if ($input->user_password_change) {
					$input->user_password_change = false; //Set password change to false to clear the checkbox in the view
					
					if ($input->user_password_new != $input->user_password_new_confirm) {
						throw new ValidationException(_('New password and confirmation didn\'t match.'));
					}
					
					if ($user->checkPassword($input->user_password) === false) {
						throw new ValidationException(_('Old password is wrong.'));
					}
					
					$user->setPassword($input->user_password_new);
				}
				
				$user->setMail($input->user_mail);
				$user->save();
				
				Message::save(_('Profile saved.'), Message::LEVEL_SUCCESS);
				$this->redirect(Uri::to('admin/profile'));
			} catch	(ValidationException $e) {
				$input->save();
				Message::save($e->getMessage(), Message::LEVEL_ERROR);
				$this->redirect(Uri::to('admin/profile'));
				exit;
			}
		} else {
			$this->redirect(Uri::to('admin/profile'));
			exit;
		}
	}
	
	public function index() {
		$this->checkPermission(Models\User::PERM_USER);
		
		$this->view->assignVar('users', Models\User::getUsers());
		$this->view->load('users');
	}
	
	public function delete() {
		$this->checkPermission(Models\User::PERM_USER);
		
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}
	
		$input = new Input(Input::POST);
		$input->filter('user_id', FILTER_SANITIZE_NUMBER_INT);
	
		try {
			$user = Models\User::load($input->user_id);
			if ($user !== false) {
				if ($user->getId() == Registry::getInstance()->user->getId()) {
					throw new ValidationException(_('You cannot delete yourself.'));
				}	
			
				$user->delete();
				Message::save(_('User deleted.'), Message::LEVEL_SUCCESS);
				$this->redirect(Uri::to('admin/user/'));
			} else {
				Message::save(_('User not found.'), Message::LEVEL_ERROR);
				$this->redirect(Uri::to('admin/user'));
				exit;
			}
		} catch (ValidationException $e) {
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/user'));
			exit;
		}
	}
	
	public function edit($userId = -1) {
		$this->checkPermission(Models\User::PERM_USER);
		
		if ($userId >= 0) {
			$user = Models\User::load($userId);
		} else {
			$user = new Models\User();
		}
		
		if ($user !== false) {
			$this->view->assignVar('editUser', $user);
			$this->view->load('user');
		} else {
			Message::save(_('User not found.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/user'));
			exit;
		}
	}
	
	public function save($userId = -1) {
		$this->checkPermission(Models\User::PERM_USER);
		
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}
		
		if ($userId >= 0) {
			$user = Models\User::load($userId);
		} else {
			$user = new Models\User();
		}
		
		$input = new Input(Input::POST);
		$input->filter('user_password_change', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		$input->filter('user_mail', FILTER_SANITIZE_EMAIL);
		$input->filter('user_active', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_permission_entry', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_permission_entry_all', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_permission_category', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_permission_comment', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_permission_settings', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_permission_user', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_permission_image', FILTER_VALIDATE_BOOLEAN);
		$input->filter('user_permission_image_edit', FILTER_VALIDATE_BOOLEAN);
		
		try {
			if ($input->user_password_change || $user->getId() < 0) {
				$input->user_password_change = false; //Set password change to false to clear the checkbox in the view
					
				$user->setPassword($input->user_password);
			}
			
			$user->setName($input->user_name);
			$user->setMail($input->user_mail);
			if (!$input->user_active && $user->getId() == Registry::getInstance()->user->getId()) {
				throw new ValidationException(_('You cannot deactivate yourself.'));
			}
			$user->setActive($input->user_active);
			$user->setPermission(Models\User::PERM_ENTRY, $input->user_permission_entry);
			$user->setPermission(Models\User::PERM_ENTRY_ALL, $input->user_permission_entry_all);
			$user->setPermission(Models\User::PERM_CATEGORY, $input->user_permission_category);
			$user->setPermission(Models\User::PERM_COMMENT, $input->user_permission_comment);
			$user->setPermission(Models\User::PERM_SETTINGS, $input->user_permission_settings);
			$user->setPermission(Models\User::PERM_USER, $input->user_permission_user);
			$user->setPermission(Models\User::PERM_IMAGE, $input->user_permission_image);
			$user->setPermission(Models\User::PERM_IMAGE_EDIT, $input->user_permission_image_edit);
			$user->save();
			
			Message::save(_('Profile saved.'), Message::LEVEL_SUCCESS);
			$this->redirect(Uri::to('admin/user/' . $user->getId()));
		} catch	(ValidationException $e) {
			$input->save();
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/user/' . (($user->getId() >= 0) ? $user->getId() : 'new')));
			exit;
		}
	}
}

?>