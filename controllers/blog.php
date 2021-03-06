<?php namespace Controllers;

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
						
		$entries = Entry::getVisibleEntries(Registry::getInstance()->settings->getEntriesPerPage(),
				($page - 1) * Registry::getInstance()->settings->getEntriesPerPage());
		
		$this->view->assignVar('entries', $entries);
		$this->view->assignVar('page', $page);
		$this->view->assignVar('page_next', Uri::to('blog/page/' . ($page + 1)));
		$this->view->assignVar('page_prev', Uri::to('blog/page/' . ($page - 1)));
		
		$this->view->load('entries');
	}
	
	public function entry($entryUri) {
		$entryUri = $entryUri;
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
	
	public function category($categoryName, $page = 1) {
		$page = filter_var($page, FILTER_SANITIZE_NUMBER_INT);
		if ($page < 1) {
			$this->redirect(Uri::to('/category/' . $categoryName));
			exit;
		}
		
		$entries = Entry::getVisibleEntriesForCategory($categoryName, Registry::getInstance()->settings->getEntriesPerPage(),
				($page - 1) * Registry::getInstance()->settings->getEntriesPerPage());
		
		$this->view->assignVar('entries', $entries);
		$this->view->assignVar('page', $page);
		$this->view->assignVar('page_next', Uri::to('category/' . $categoryName . '/page/' . ($page + 1)));
		$this->view->assignVar('page_prev', Uri::to('category/' . $categoryName . '/page/' . ($page - 1)));
		
		$this->view->load('entries');
	}
	
	public function feed() {
		$entries = Entry::getVisibleEntries(Registry::getInstance()->settings->getEntriesPerPage());
		$this->view->assignVar('entries', $entries);
		$this->view->load('feed');
	}
}

?>