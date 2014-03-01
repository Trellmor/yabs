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

use Application\Uri;
use Application\Registry;
use Models\Message;
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