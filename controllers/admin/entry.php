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
use Application\Registry;
use Application\Uri;
use Application\Exceptions\ValidationException;
use Models;
use Models\Category;
use Models\Message;
use Models\User;

class Entry extends AdminController {
	public function __construct() {
		parent::__construct();

		$this->checkPermission(User::PERM_ENTRY);
	}

	public function index($page = 1) {
		if ($page < 1) {
			$this->redirect(Uri::to('/admin/entry/'));
			exit;
		}

		$offset = ($page - 1) * 15;

		if (Registry::getInstance()->user->hasPermission(User::PERM_ENTRY_ALL)) {
			$entries = Models\Entry::getEntries(15, $offset);
		} else {
			$entries = Models\Entry::getEntriesForUser(Registry::getInstance()->user->getUserId(), 15, $offset);
		}

		$this->view->assignVar('entries', $entries);
		$this->view->assignVar('page', $page);
		$this->view->load('entries');
	}

	public function edit($entryId) {
		$entry = Models\Entry::getEntry($entryId);
		if ($entry === false) {
			$this->error(404, _('Entry not found.'));
			die;
		}

		if (!Registry::getInstance()->user->hasPermission(User::PERM_ENTRY_ALL)) {
			if ($entry->getUserId() != Registry::getInstance()->user->getId()) {
				$this->error(403, _('Access denied.'));
				die();
			}
		}

		$this->editEntry($entry);
	}

	public function editEntry($entry) {
		$this->view->assignVar('entry', $entry);
		$this->view->assignVar('categories', $this->getCategories());
		$this->view->load('entry');
	}

	public function create() {
		$entry = new Models\Entry();
		$entry->setDate(time());
		$this->editEntry($entry);
	}

	private function loadEntry($entryId) {
		if ($entryId === false) {
			throw new ValidationException(_('Entry not found.'));
		}

		$entry = Models\Entry::getEntry($entryId);
		if ($entry === false) {
			throw new ValidationException(_('Entry not found.'));
		}

		if (!Registry::getInstance()->user->hasPermission(User::PERM_ENTRY_ALL)) {
			if ($entry->getUserId() != Registry::getInstance()->user->getId()) {
				throw new ValidationException(_('Access denied.'));
			}
		}

		return $entry;
	}

	public function delete() {
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}

		$input = new Input(Input::POST);
		$input->filter('entry_id', FILTER_SANITIZE_NUMBER_INT);
		$input->filter('page', FILTER_SANITIZE_NUMBER_INT);

		try {
			$entry = $this->loadEntry($input->entry_id);
				
			if ($entry !== false) {			
				$entry->delete();
				Message::save(_('Entry deleted.'), Message::LEVEL_SUCCESS);
				$this->redirect(Uri::to('admin/entry/page/' . ((int) $input->page)));
			} else {
				Message::save(_('Entry not found.'), Message::LEVEL_ERROR);
				$this->redirect(Uri::to('admin/entry/page/' . ((int) $input->page)));
				exit;
			}
		} catch (ValidationException $e) {
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry/page/' . ((int) $input->page)));
			exit;
		}
	}

	public function save($entryId) {
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Save failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}

		try {
			$entry = $this->loadEntry($entryId);
		} catch (ValidationException $e) {
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}

		try {
			$input = new Input(Input::POST);
			$this->fillEntry($entry, $input);

			$entry->save();
			Message::save(_('Entry saved.'), Message::LEVEL_SUCCESS);
			$this->redirect(Uri::to('admin/entry/' . $entry->getId()));
		} catch (ValidationException $e) {
			$input->save();
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry/' . $entry->getId()));
			exit;
		}
	}

	private function fillEntry($entry, $input) {
		$input->filter('entry_title', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		$input->filter('entry_uri', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		if (empty($input->entry_uri)) {
			$input->entry_uri = $input->entry_title;
		}
		$input->entry_uri = substr($input->entry_uri, 0, 36);
		$input->filter('category_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
		$input->filter('entry_visible', FILTER_VALIDATE_BOOLEAN);
			
		$entry->setTitle($input->entry_title);
		$entry->setContent($input->entry_content);
		$entry->setTeaser((empty($input->entry_teaser)) ? null : $input->entry_teaser);
		$date = \DateTime::createFromFormat('Y-m-d H:i', $input->entry_date);
		$entry->setDate($date->getTimestamp());
		$entry->setUri($input->entry_uri);
		$entry->setVisible($input->entry_visible);
		$entry->setCategoryId($input->category_id);
	}

	public function saveNew() {
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Save failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry/new'));
			exit;
		}

		$entry = new Models\Entry();
		$input = new Input(Input::POST);

		try {
			$this->fillEntry($entry, $input);
			$entry->setUserId(Registry::getInstance()->user->getId());
			$entry->save();
			$this->redirect(Uri::to('admin/entry/' . $entry->getId()));
		} catch (ValidationException $e) {
			$input->save();
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry/new'));
			exit;
		}
	}

	private function getCategories() {
		$result = array();
		$categories = Category::getCategories();
		$result[''] = '';
		foreach ($categories as $category) {
			$result[$category->getId()] = $category->getName();
		}
		return $result;
	}
}

?>