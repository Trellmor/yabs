<?php namespace View;

use Application\CSRF;

class Forms {
	public static function form($action) {
		$csrf = new CSRF();
		$form = '<form action="' . $action . '" method="post">';
		$form .= static::inputHidden($csrf->getName(), $csrf->getToken());
		return $form;
	}
	
	public static function input($type, $name, $value = null, $attrib = null) {
		switch ($type) {
			case 'text':
			case 'hidden':
			case 'submit':
				return '<input' . static::getAttributes(['type' => $type, 'id' => $name, 'name' => $name, 'value' => $value]) . static::getAttributes($attrib) . ' />';
			case 'password':
				return static::inputPassword($name, $attrib);
			case 'textarea':
				return static::textarea($name, $value, $attrib);
		}
	}

	public static function inputText($name, $value, $attrib = null) {
		return static::input('text', $name, $value, $attrib);
	}
	
	public static function inputPassword($name, $attrib = null) {
		return '<input id="' . $name . '" type="password" name="' . $name . '" />';
	}
	
	public static function inputSubmit($name, $value, $attrib = null) {
		return static::input('submit', $name, $value, $attrib);
	}
	
	public static function inputHidden($name, $value, $attrib = null) {
		return static::input('hidden', $name, $value, $attrib);
	}
	
	public static function textarea($name, $value, $attrib = null) {
		return '<textarea' . static::getAttributes(['id' => $name, 'name' => $name]) . static::getAttributes($attrib) . '>' . filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '</textarea>';
	}
	
	public static function getAttributes($attrib) {
		$result = '';
		if ($attrib !== null) {
			foreach ($attrib as $name => $value) {
				$result .= ' ' . $name . '="' . filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '"';
			}
		}
		return $result;
	}
}

?>