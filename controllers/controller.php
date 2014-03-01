<?php namespace Controllers;

use Models\Message;
use Application\Uri;
use Application\Registry;
use View\View;

abstract class Controller {
	protected $view;

	public function __construct() {
		$template = Registry::getInstance()->template;
		$this->view = new View($template);
		$this->view->assignVar('settings', Registry::getInstance()->settings);
		$this->view->assignVar('page_title', Registry::getInstance()->settings->getSiteTitle());
	}

	protected function error($code, $message) {
		http_response_code($code);

		$this->message(new Message($message, Message::LEVEL_ERROR));
	}

	protected function info($message) {
		$this->message(new Message($message, Message::LEVEL_INFO));
	}

	protected function success($message) {
		$this->message(new Message($message, Message::LEVEL_SUCCESS));
	}

	protected function warn($message) {
		$this->message(new Message($message, Message::LEVEL_WARNING));
	}

	protected function message($message) {
		$this->view->assignVar('message', $message);
		$this->view->load('messagepage');
	}

	protected function redirect($uri) {
		header('Location: ' . $uri);
	}
}

?>