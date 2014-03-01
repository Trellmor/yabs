<?php namespace View;

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

use Application\Exceptions\InvalidViewException;
use Models\Message;

class View {
	private $template;
	private $vars = array();
	
	public function __construct($template) {
		$this->template = $template;
		$this->assignVar('view', $this);
	}
	
	public function getTemplate() {
		return $this->template;
	}
	
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	public function assignVar($name, $value) {
		$this->vars[$name] = $value;
	}
	
	public function load($view) {
		$file = __DIR__ . '/' . $this->template . '/' . $view . '.php';
		
		if (file_exists($file)) {
			extract($this->vars);
			include $file;
		} else {
			throw new InvalidViewException('View not found: ' . $view);
		}		
	}
		
	public function handleMessages() {
		$messages = Message::getSavedMessages();
	
		foreach ($messages as $message) {
			$this->assignVar('message', $message);
			$this->load('message');
		}
	}
}

?>