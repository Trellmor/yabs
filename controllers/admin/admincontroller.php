<?php namespace Controllers\Admin;

use Application\CSRF;
use Application\Session;
use Application\Uri;
use Application\Registry;
use Controllers\Controller;

class AdminController extends Controller {
	protected $csrf;
	
	public function __construct() {
		parent::__construct();
		$this->view->setTemplate('admin');
		
		if (!isset(Registry::getInstance()->user) || Registry::getInstance()->user == null) {
			Session::destroy();
			$this->redirect(Uri::to('admin/login'));
			die();
		}
		
		$this->csrf = new CSRF();
		
		$this->view->assignVar('user', Registry::getInstance()->user);
		$this->view->assignVar('settings', Registry::getInstance()->settings);
		$this->view->assignVar('csrf', $this->csrf);
	}
	
	public function index() {
		$this->view->load('header');
		$this->view->load('dashboard');
		$this->handleMessage();
		$this->view->load('footer');
	}
	
	protected function checkPermission($permission) {
		if (Registry::getInstance()->user->hasPermission($permission)) {
			return true;
		} else {
			$this->error(403, _('Access denied.'));
			die();
		}
	}
}

?>