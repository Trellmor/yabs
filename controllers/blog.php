<?php namespace Controllers;

use Application\Registry;
use Application\Uri;
use Models\Entry;
use Models\Comment;
use Models\CommentAuthor;

class Blog extends Controller {
	
	public function index($page = 1) {
		$page = filter_var($page, FILTER_SANITIZE_NUMBER_INT);
		if ($page < 1) {
			$this->redirect(Uri::to('/blog/'));
			exit;
		}
		
		$entry = new Entry();		
		$entries = $entry->getVisibleEntries(Registry::getInstance()->settings->getEntriesPerPage(),
				($page - 1) * Registry::getInstance()->settings->getEntriesPerPage());
		
		$this->view->assignVar('entries', $entries);
		$this->view->assignVar('page', $page);
		$this->view->assignVar('page_next', Uri::to('blog/page/' . ($page + 1)));
		$this->view->assignVar('page_prev', Uri::to('blog/page/' . ($page - 1)));
		
		$this->view->load('entries');
	}
	
	public function entry($entryUri) {
		$entryUri = urldecode($entryUri);
		$entry = Entry::getEntryByUri($entryUri);
		
		if ($entry !== false) {
			$this->view->assignVar('entry', $entry);
			
			$comments = array();
			$comments = Comment::getCommentsForEntry($entry->getId());
						
			$this->view->assignVar('comments', $comments);
			$this->view->assignVar('commentAuthor', new CommentAuthor());
			$this->view->assignVar('page_title', $entry->getTitle() . ' - ' . Registry::getInstance()->settings->getSiteTitle());
						
			$this->view->load('entry');
		} else {
			$this->error(404, _('Entry not found.'));
			return;
		}
	}
}

?>