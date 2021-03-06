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

class Image extends AdminController {
	public function __construct() {
		parent::__construct();

		$this->checkPermission(User::PERM_IMAGE);
	}
	
	
	public function index($page = 1) {
		if ($page < 1) {
			$this->redirect(Uri::to('/admin/iamge/'));
			exit;
		}
		
		$input = new Input(Input::GET);
		$input->filter("q", FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
		if ($input->order == 'date') {
			$order = 'date';
		} else if ($input->order == 'name') {
			$order = 'name';
		} else {
			$order = null;
		}
		
		$query = $input->q;
		
		$images = Models\Image::getImages(9, ($page - 1) * 9, $query, $order);
		
		$this->view->assignVar('images', $images);
		$this->view->assignVar('query', $query);
		$this->view->assignVar('order', $order);
		$this->view->assignVar('page', $page);
		$this->view->load('images');
	}
	
	public function upload() {
		$this->checkPermission(User::PERM_IMAGE_EDIT);
				
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}
		
		try {
			if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
				throw new ValidationException(_('Upload failed.'));
			}
			
			$image = new Models\Image();
			$image->upload($_FILES['file']['name'], $_FILES['file']['tmp_name']);
			
			Message::save(_('Image saved.'), Message::LEVEL_SUCCESS);
			$this->redirect(Uri::to('admin/image/' . $image->getId()));
		} catch (ValidationException $e) {
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/image'));
		}
	}
	
	public function image($imageId) {
		$image = Models\Image::getImage($imageId);
		
		if ($image !== false) {
			$this->view->assignVar('image', $image);
			$this->view->load('image');
		} else {
			Message::save(_('Image not found.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/image'));
		}
	}
}

?>