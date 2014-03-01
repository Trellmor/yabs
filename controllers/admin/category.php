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
use Application\Input;
use Application\Uri;
use Models;
use Models\User;
use Models\Message;

class Category extends AdminController {
	public function __construct() {
		parent::__construct();
		
		$this->checkPermission(User::PERM_CATEGORY);
	}
	
	public function index() {		
		$this->view->assignVar('categories', Models\Category::getCategories());
		$this->view->load('categories');
	}
	
	public function edit($categoryId = -1) {
		if ($categoryId >= 0) {
			$category = Models\Category::getCategory($categoryId);
		} else {
			$category = new Models\Category();
		}
		if ($category !== false) {
			$this->view->assignVar('category', $category);
			$this->view->load('category');
		} else {
			Message::save(_('Category not found.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/category'));
		}
	}	
	
	public function save($categoryId = -1) {
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/category'));
			exit;
		}
		
		if ($categoryId >= 0) {
			$category = Models\Category::getCategory($categoryId);
		} else {
			$category = new Models\Category();
		}
		if ($category !== false) {
			$input = new Input(Input::POST);
			$input->filter('category_name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
			try {
				$category->setName($input->category_name);
				$category->save();
				
				Message::save(_('Category saved.'), Message::LEVEL_SUCCESS);
				$this->redirect(Uri::to('admin/category/' . $category->getId()));
			} catch (ValidationException $e) {
				$input->save();
				Message::save($e->getMessage(), Message::LEVEL_ERROR);
				$this->redirect(Uri::to('admin/category/' . (($categoryId >= 0) ? $categoryId : 'new')));
			}
		} else {
			Message::save(_('Category not found.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/category'));
		}
	}
	
	public function delete() {	
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/category'));
			exit;
		}
	
		$input = new Input(Input::POST);
		$input->filter('category_id', FILTER_SANITIZE_NUMBER_INT);
	
		$category = Models\Category::getCategory($input->category_id);
		if ($category !== false) {
			$category->delete();
		
			Message::save(_('Category deleted.'), Message::LEVEL_SUCCESS);
			$this->redirect(Uri::to('admin/category'));
		} else {
			Message::save(_('Category not found.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/category'));
		}
	}
}

?>