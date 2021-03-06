<?php namespace Controllers;

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

use Application\CSRF;
use Application\Session;
use Application\Input;
use Application\Registry;
use Application\Uri;
use Models\Message;
use Models\User;

class Login extends Controller {	
	public function index() {
		if ($this->isLoggedIn()) {
			$this->redirect(Uri::to('admin'));
			exit;
		}
		
		//Check if the user has a remember me cookie
		if (isset($_COOKIE['yabs_user_remember'])) {
			$data = json_decode($_COOKIE['yabs_user_remember'], true);
			if ($data != null && isset($data['user_id']) && isset($data['user_remember_token'])) {
				$data['user_remember_token'] = base64_decode($data['user_remember_token'], true);
				if ($data['user_remember_token'] !== false) {				
					if (($user = User::verifyRememberToken($data['user_id'], $data['user_remember_token'])) !== false) {
						//Log user in
						Session::start();
						$_SESSION['user_id'] = $user->getId();
						$this->setRememberCookie($user);
						$this->redirect(Uri::to('admin'));
						exit;
					}
				}
			}
		}
		
		$this->clearRememberCookie();
		$this->view->load('login');
	}

	private function isLoggedIn() {
		return isset(Registry::getInstance()->user) && Registry::getInstance()->user != null;
	}
	
	public function login() {
		$csrf = new CSRF();
		if (!$csrf->verifyToken()) {
			$this->redirect(Uri::to('/'));
			exit;
		}
		
		$input = new Input('POST');
		if (($user = User::verifyLogin($input->user_name, $input->user_password)) !== false) {
			Session::start();
			$_SESSION['user_id'] = $user->getId();
			
			$input->filter('user_remember', FILTER_VALIDATE_BOOLEAN);
			if ($input->user_remember) {
				$this->setRememberCookie($user);
			}
			
			$this->redirect(Uri::To('admin'));
		} else {
			Message::save(_('Login failed.'));
			$this->redirect(Uri::to('admin/login'));
			return;
		}
	}
	
	private function clearRememberCookie() {
		if (isset($_COOKIE['yabs_user_remember'])) {
			unset($_COOKIE['yabs_user_remember']);
			setcookie(
					'yabs_user_remember',
					'',
					time() - 42000,
					'/'
			);
		}
	}
	
	public function setRememberCookie($user) {
		$data = array(
				'user_id' => $user->getId(),
				'user_remember_token' => base64_encode($user->generateRememberToken())
			);
			setcookie(
					'yabs_user_remember',
					json_encode($data),
					time() + 60 * 60 * 24 * 30,
					'/'
			);
	}
	
	public function logout() {
		$this->clearRememberCookie();
		
		Session::destroy();
		
		$this->info(_('Logged out.'));
	}
}

?>