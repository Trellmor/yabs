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
		$this->view->load('dashboard');
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