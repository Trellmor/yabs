<?php namespace Controllers;

use Models\Message;

use Application\Registry;
use Application\CSRF;
use Application\Uri;
use Application\Input;
use Application\Exceptions\ValidationException;
use Models;
use Controllers\Controller;
use View\HTML;

class Comment extends Controller {
	public function add() {
		$csrf = new CSRF();
		if (!$csrf->verifyToken()) {
			$this->redirect(Uri::to('/'));
			exit;
		}
		
		$get = new Input('GET');
		$post = new Input('POST');
		
		$get->filter('entry_id', FILTER_VALIDATE_INT);
		if ($get->entry_id == false) {
			$this->error(200, _('Invalid entry.'));
		}
		if (($entry = Models\Entry::getEntry($get->entry_id)) === false) {
			$this->error(200, _('Invalid entry.'));
		}		
		$post->filter('comment_author', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		$post->filter('comment_mail', FILTER_SANITIZE_EMAIL);
		$post->filter('comment_url', FILTER_SANITIZE_URL);
				
		if (empty($post->comment_url)) $post->comment_url = null;
		$post->comment_text = $this->loadHTMLPurifier()->purify($post->comment_text);
		
		$comment = new Models\Comment();
		try {		
			$comment->setEntryId($get->entry_id);
			$comment->setAuthor($post->comment_author);
			$comment->setMail($post->comment_mail);
			$comment->setUrl($post->comment_url);
			$comment->setText($post->comment_text);
			$comment->setDate(time());
			$comment->setIP($_SERVER['REMOTE_ADDR']);
			$comment->setVisible(true);
			
			if (Registry::getInstance()->settings->akismet) {
				$this->akismet($comment, $entry);
			} else {			
				$comment->setSpam(false);
			}
			$comment->save();
		
			$this->redirect(Uri::to('blog/' . HTML::filter($entry->getUri())) . '#com' . $comment->getId());
		} catch (ValidationException $e) {
			$post->save();
			Message::save($e->getMessage(), Message::LEVEL_ERROR);
			$this->redirect(Uri::to('blog/' . HTML::filter($entry->getUri())) . '#add');
		}
	}
	
	private function akismet($comment, $entry) {
		require_once APP_ROOT . '/libs/Akismet/Akismet.class.php';
		$akismet = new \Akismet(Uri::to(''), Registry::getInstance()->settings->akismet_key);
		$akismet->setCommentAuthor($comment->getAuthor());
		$akismet->setCommentAuthorEmail($comment->getMail());
		$akismet->setCommentAuthorURL($comment->getUrl());
		$akismet->setCommentContent($comment->getText());
		$akismet->setPermalink(Uri::to('blog/' . HTML::filter($entry->getUri())));
		$comment->setSpam($akismet->isCommentSpam());
	}
	
	private function loadHTMLPurifier() {
		require_once APP_ROOT . '/libs/HTMLPurifier/HTMLPurifier.auto.php';
		$config = \HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', 'a[href],b,i,u,strike,blockquote,code,br,p,strong,pre');
		$config->set('AutoFormat.Linkify', true);
		$config->set('AutoFormat.AutoParagraph', true);
		$config->set('Cache.SerializerPath', APP_ROOT . '/cache/HTMLPurifier');
		return new \HTMLPurifier($config);
	} 
}

?>