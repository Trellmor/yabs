<?php namespace Controllers;

use Application\Uri;
use Models\Entry;
use Models\Comment;

class Blog extends Controller {
	
	public function index($page = 1) {
		$page = filter_var($page, FILTER_SANITIZE_NUMBER_INT);
		
		$entry = new Entry();		
		$entries = $entry->getVisibleEntries(10, ($page - 1) * 10);
		
		$this->view->assignVar('entries', $entries);
		$this->view->assignVar('page', $page);
		$this->view->assignVar('page_next', Uri::to('blog/page/' . ($page + 1)));
		$this->view->assignVar('page_prev', Uri::to('blog/page/' . ($page - 1)));
		
		$this->view->load('header');
		$this->view->load('entries');
		$this->view->load('footer');
	}
	
	public function entry($entry_uri) {
		$entry_uri = filter_var($entry_uri, FILTER_SANITIZE_STRING);
		$entry = Entry::getEntryByUri($entry_uri);
		
		if ($entry !== false) {
			$this->view->assignVar('entry', $entry);
			
			$comments = array();
			$comments = Comment::getComments($entry->getId());
			$this->view->assignVar('comments', $comments);
			
			$this->view->load('header');
			$this->view->load('entry');
			$this->view->load('comments');
			$this->view->load('footer');
		} else {
			$this->error(404, _('Entry not found.'));
			return;
		}
	}
}

?>