<?php namespace View;

use Application\CSRF;

class Forms {
	public static function form($action) {
		$csrf = new CSRF();
		$form = '<form action="' . $action . '" method="post">';
		$form .= '<input type="hidden" id="' . $csrf->getName() . '" name="' . $csrf->getName() . '" value="' . $csrf->getToken() . '" />';
		return $form;
	}
	
	public static function input($type, $name, $value = null) {
		switch ($type) {
			case 'text':
				return static::inputText($name, $value);
			case 'password':
				return static::inputPassword($name);
			case 'submit':
				return static::inputSubmit($name, $value);
		}
	}

	public static function inputText($name, $value) {
		return '<input id="' . $name . '" type="text" name="' . $name . '" value="' . filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '" />';
	}
	
	public static function inputPassword($name) {
		return '<input id="' . $name . '" type="password" name="' . $name . '" />';
	}
	
	public static function inputSubmit($name, $value) {
		return '<input id="' . $name . '" type="submit" name="' . $name . '" value="' . filter_Var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '" />';
	}
}

?>