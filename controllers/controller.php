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
	
	protected function message() {
		$this->view->assignVar('message', $message);
		$this->view->load('header');
		$this->view->load('message');
		$this->view->load('footer');
	}
	
	protected function redirect($url) {
		header('Location: ' . $url);
	}
	
	protected function internalRedirect($url, $method = 'GET') {
		Registry::getInstance()->router->route($method, $url);
	}
	
	protected function handleMessage() {
		if (isset(Registry::getInstance()->message)) {
			$this->view->assignVar('message', Registry::getInstance()->message);
			$this->view->load('message');
		}
	}
}

?>