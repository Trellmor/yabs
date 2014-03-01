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

use Application\Input;
use Application\Uri;
use Application\Exceptions\ValidationException;
use Models;
use Models\Message;
use Models\User;

class Settings extends AdminController {
	public function index() {
		$this->checkPermission(User::PERM_SETTINGS);
		
		$this->view->load('settings');
	}
	
	public function save() {
		$this->checkPermission(User::PERM_SETTINGS);
		
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Save failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/settings'));
			exit;
		}
		
		$input = new Input(Input::POST);
		$input->filter('akismet', FILTER_VALIDATE_BOOLEAN);
		$input->filter('akismet_key', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
		$input->filter('site_title', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		$input->filter('entries_per_page', FILTER_SANITIZE_NUMBER_INT);
		$input->filter('datetime_format', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		$input->filter('language', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_LOW);
		$input->filter('template', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_LOW);
		
		$settings = Models\Settings::load();
		try {
			$settings->setAkismet(isset($input->akismet));
			$settings->setAkismetKey($input->akismet_key);
			$settings->setSiteTitle($input->site_title);
			$settings->setEntriesPerPage($input->entries_per_page);
			$settings->setDateTimeFormat($input->datetime_format);
			$settings->setLanguage($input->language);
			$settings->setTemplate($input->template);
			
			$settings->save();
			Message::save(_('Settings saved.'), Message::LEVEL_SUCCESS);
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