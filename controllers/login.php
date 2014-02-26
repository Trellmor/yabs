<?php namespace Controllers;

use Application\CSRF;

use Application\Session;
use Application\Input;
use Application\Registry;
use Application\Uri;
use Models\Message;
use Models\User;

class Login extends Controller {	
	public function index() {
		$this->view->load('login');
	}

	public function login() {
		$csrf = new CSRF();
		if (!$csrf->verifyToken()) {
			$this->redirect(Uri::to('/'));
			exit;
		}
		
		$input = new Input('POST');
		if (($user = User::verifyLogin($input->username, $input->password)) !== false) {
			Session::start();
			$_SESSION['user_id'] = $user->getId();
			
			$this->redirect(Uri::To('admin'));
		} else {
			Message::save(_('Login failed.'));
			$this->redirect(Uri::to('admin/login'));
			return;
		}
	}
	
	public function logout() {
		Session::destroy();
		$this->info(_('Logged out.'));
	}
}

?>