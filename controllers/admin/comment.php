<?php namespace Controllers\Admin;

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
	
	public function index($page = 1) {
	
		if ($page < 1) {
			$this->redirect(Uri::to('/admin/comment/'));
			exit;
		}
		
		$comments = Models\Comment::getComments(15, ($page - 1) * 15);
		
		$this->view->assignVar('comments', $comments);
		$this->view->assignVar('page', $page);
		$this->view->load('header');
		$this->handleMessage();
		$this->view->load('comments');
		$this->view->load('footer');
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
	
	
	
	public function delete() {
		if (!$this->csrf->verifyToken()) {
			Message::save(_('Delete failed.'), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/comment'));
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
				$this->redirect(Uri::to('admin/comment/page/' . ((int) $input->page)));
			} else {
				Message::save(_('Comment not found.'), Message::LEVEL_ERROR);
				$this->redirect(Uri::to('admin/comment/page/' . ((int) $input->page)));
				exit;
			}
			
		} catch (ValidationException $e) {
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('admin/comment/page/' . ((int) $input->page)));
			exit;
		}
	}
	
	private function json(array $data) {
		header('Content-type: application/json; charset=UTF-8');
		echo json_encode($data);
	}
	
	private function jsonError($error) {
		$this->json(['status' => 'error', 'message' => $error]);
	}
}

?>