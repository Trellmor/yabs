<?php namespace View;

use Application\Exceptions\InvalidViewException;

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
}

?>