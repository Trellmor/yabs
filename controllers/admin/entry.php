<?php namespace Controllers\Admin;

use Application\CSRF;
use Application\Input;
use Application\Registry;
use Application\Uri;
use Application\Exceptions\ValidationException;
use Models;
use Models\Message;
use Models\User;
use Controllers\Admin\AdminController;

class Entry extends AdminController {
	public function index($page = 1) {
		$this->checkPermission(User::PERM_ENTRY_WRITE);
		$offset = ($page - 1) * 30;
		
		if (Registry::getInstance()->user->hasPermission(User::PERM_ENTRY_ALL)) {
			$entries = Models\Entry::getEntries(30, $offset);
		} else {	
			$entries = Models\Entry::getEntriesForUser(Registry::getInstance()->user->getUserId(), 30, $offset);
		} 
		
		$this->view->assignVar('entries', $entries);
		$this->view->assignVar('page', $page);
		$this->view->load('header');
		$this->handleMessage();
		$this->view->load('entries');
		$this->view->load('footer');
	}
	
	public function edit($entryId) {
		$this->checkPermission(User::PERM_ENTRY_WRITE);
		
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
		
		$this->view->assignVar('entry', $entry);
		$this->view->load('header');
		$this->handleMessage();
		$this->view->load('entry');
		$this->view->load('footer');
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
		$this->checkPermission(User::PERM_ENTRY_DELETE);
		
		$csrf = new CSRF();
		if (!$csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}
		
		$input = new Input(Input::POST);
		$input->filter('entry_id', FILTER_SANITIZE_NUMBER_INT);
		$input->filter('page', FILTER_SANITIZE_NUMBER_INT);
		
		try {
			$entry = $this->loadEntry($input->entry_id);
			
			$entry->delete();
			Message::save(_('Entry deleted.'), Message::LEVEL_SUCCESS);
			$this->redirect(Uri::to('admin/entry/page/' . ((int) $input->page)));
		} catch (ValidationException $e) {
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry/page/' . ((int) $input->page)));
			exit;
		}
	}
	
	public function save($entryId) {
		$this->checkPermission(User::PERM_ENTRY_WRITE);
		
		$csrf = new CSRF();
		if (!$csrf->verifyToken()) {
			Message::save(_('Save failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}
				
		try {
			$entry = $this->loadEntry($entryId);

			$input = new Input(Input::POST);
			$input->filter('entry_title', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_AMP);
			
			$entry->setTitle($input->entry_title);
			$entry->setContent($input->entry_content);
			$entry->setTeaser((empty($input->entry_teaser)) ? null : $input->entry_teaser);
						
			$entry->save();
			Message::save(_('Entry saved.'), Message::LEVEL_SUCCESS);
			$this->redirect(Uri::to('admin/entry/' . $entry->getId()));
		} catch (ValidationException $e) {
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/entry'));
			exit;
		}
	}
}

?>