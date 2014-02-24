<?php namespace Controllers\Admin;

use Application\CSRF;
use Application\Input;
use Application\Uri;
use Models;
use Models\User;

class Settings extends AdminController {
	public function index() {
		$this->checkPermission(User::PERM_SETTINGS);
		
		$this->view->load('header');
		$this->handleMessage();
		$this->view->load('settings');
		$this->view->load('footer');
	}
	
	public function save() {
		$this->checkPermission(User::PERM_SETTINGS);
		
		$csrf = new CSRF();
		if (!$csrf->verifyToken()) {
			Message::save(_('Save failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/settings'));
			exit;
		}
		
		$input = new Input(Input::POST);
		$input->filter('akismet_key', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
		$input->filter('site_title', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		
		$settings = Models\Settings::load();
		try {
			$settings->setAkismet(isset($input->akismet));
			$settings->setAkismetKey($input->akismet_key);
			$settings->setSiteTitle($input->site_title);
			
			$settings->save();
			$this->redirect(Uri::to('admin/settings'));
		} catch (ValidationException $e) {
			$input->save();
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/settings'));
			exit;
		}
	}
}

?>