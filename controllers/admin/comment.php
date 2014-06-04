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
use Models\User;
use Models\Message;

class Comment extends AdminController {
	public function __construct() {
		parent::__construct();
		
		$this->checkPermission(User::PERM_COMMENT);
	}
	
	public function index($page = 1, $spam = false) {
		$spamUrl = ($spam) ? 'spam/' : '';
		if ($page < 1) {
			$this->redirect(Uri::to('/admin/comment/' . $spamUrl));
			exit;
		}
		
		$comments = Models\Comment::getComments($spam, 15, ($page - 1) * 15);
		
		$this->view->assignVar('comments', $comments);
		$this->view->assignVar('page', $page);
		$this->view->assignVar('spam', $spam);
		$this->view->load('comments');
	}
	
	public function indexSpam($page = 1) {
		$this->index($page, true);
	}
	
	public function toggleSpam() {
		try {
			if (!$this->csrf->verifyToken()) {
				throw new ValidationException(_('Save failed.'));
			}
			
			$input = new Input(Input::POST);
			$input->filter('comment_id', FILTER_SANITIZE_NUMBER_INT);
			$comment = Models\Comment::getComment($input->comment_id);
			if ($comment === false) {
				throw new ValidationException(_('Comment not found.'));
			}			
			$comment->setSpam(!$comment->isSpam());
			$comment->save();
			$this->json(['status' => 'success', 'message' => _('Comment saved.'), 'comment_spam' => $comment->isSpam()]);
		} catch (ValidationException $e) {
			$this->jsonError($e->getMessage());
		} catch (\PDOException $e) {
			$this->jsonError(_('Save failed.'));
		}
	}
	
	public function toggleVisible() {
		try {
			if (!$this->csrf->verifyToken()) {
				throw new ValidationException(_('Save failed.'));
			}
				
			$input = new Input(Input::POST);
			$input->filter('comment_id', FILTER_SANITIZE_NUMBER_INT);
			$comment = Models\Comment::getComment($input->comment_id);
			if ($comment === false) {
				throw new ValidationException(_('Comment not found.'));
			}
			$comment->setVisible(!$comment->isVisible());
			$comment->save();
			$this->json(['status' => 'success', 'message' => _('Comment saved.'), 'comment_visible' => $comment->isVisible()]);
		} catch (ValidationException $e) {
			$this->jsonError($e->getMessage());
		} catch (\PDOException $e) {
			$this->jsonError(_('Save failed.'));
		}
	}
	
	private function deleteComment($uri) {
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to($uri));
			exit;
		}
		
		$input = new Input(Input::POST);
		$input->filter('comment_id', FILTER_SANITIZE_NUMBER_INT);
		$input->filter('page', FILTER_SANITIZE_NUMBER_INT);
		
		try {
			$comment = Models\Comment::getComment($input->comment_id);
		
			if ($comment !== false) {
				$comment->delete();
				Message::save(_('Comment deleted.'), Message::LEVEL_SUCCESS);
				$this->redirect(Uri::to($uri . '/page/' . ((int) $input->page)));
			} else {
				Message::save(_('Comment not found.'), Message::LEVEL_ERROR);
				$this->redirect(Uri::to($uri . '/page/' . ((int) $input->page)));
				exit;
			}
			
		} catch (ValidationException $e) {
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to($uri . '/page/' . ((int) $input->page)));
			exit;
		}
	}
	
	public function delete() {
		$this->deleteComment('admin/comment');
	}
	
	public function deleteSpam() {
		$this->deleteComment('admin/comment/spam');
	}
	
	private function json(array $data) {
		header('Content-type: application/json; charset=UTF-8');
		echo json_encode($data);
	}
	
	private function jsonError($error) {
		$this->json(['status' => 'error', 'message' => $error]);
	}
	
	public function comment($commentId) {
		$comment = Models\Comment::getComment($commentId);
		if ($comment !== false) {
			$this->view->assignVar('comment', $comment);
			$this->view->load('comment');
		} else {
			$this->error(404, _('Comment not found.'));
		}
	}
}

?>