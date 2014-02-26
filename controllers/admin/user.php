<?php namespace Controllers\Admin;

use Application\Exceptions\ValidationException;
use Application\Registry;
use Application\Input;
use Application\Uri;
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
					
					if (Models\User::verifyLogin($user->getName(), $input->user_password) === false) {
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
}

?>