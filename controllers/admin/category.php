<?php namespace Controllers\Admin;

use Application\CSRF;
use Application\Input;
use Application\Uri;
use Models;
use Models\User;
use Models\Message;

class Category extends AdminController {
	public function index() {
		$this->checkPermission(User::PERM_CATEGORY);
		
		$this->view->assignVar('categories', Models\Category::getCategories());
		$this->view->load('header');
		$this->handleMessage();
		$this->view->load('categories');
		$this->view->assignVar('category', new Models\Category());
		$this->view->load('footer');
	}
	
	public function edit($categoryId = -1) {
		$this->checkPermission(User::PERM_CATEGORY);
		
		if ($categoryId >= 0) {
			$category = Models\Category::getCategory($categoryId);
		} else {
			$category = new Models\Category();
		}
		if ($category !== false) {
			$this->view->assignVar('category', $category);
			$this->view->load('header');
			$this->handleMessage();
			$this->view->load('category');
			$this->view->load('footer');
		} else {
			Message::save(_('Category not found.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/category'));
		}
	}	
	
	public function save($categoryId = -1) {
		$this->checkPermission(User::PERM_CATEGORY);
		
		$csrf = new CSRF();
		if (!$csrf->verifyToken()) {
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
				var_dump($category);
				$category->save();
				
				Message::save(_('Category saved.'), Message::LEVEL_SUCCESS);
				$this->redirect(Uri::to('admin/category/' . $category->getId()));
			} catch (ValidationException $e) {
				Message::save($e->getMessage(), Message::LEVEL_ERROR);
				$this->redirect(Uri::to('admin/category/' . (($categoryId >= 0) ? $categoryId : 'new')));
			}
		} else {
			Message::save(_('Category not found.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/category'));
		}
	}
	
	public function delete() {
		$this->checkPermission(User::PERM_CATEGORY_DELETE);
	
		$csrf = new CSRF();
		if (!$csrf->verifyToken()) {
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