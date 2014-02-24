<?php namespace View;

use Application\Registry;

use Application\CSRF;

class Forms {
	public static function form($action, $attrib = null) {
		$csrf = new CSRF();
		$form = '<form' . static::getAttributes(static::arrayMerge(['action' => $action, 'method' => 'post'], $attrib)) . '>';
		$form .= static::inputHidden($csrf->getName(), $csrf->getToken());
		return $form;
	}
	
	public static function input($type, $name, $value = null, $attrib = null) {
		switch ($type) {
			case 'text':
			case 'hidden':
			case 'submit':
				$value = static::checkValue($name, $value);
				return '<input' . static::getAttributes(['type' => $type, 'id' => $name, 'name' => $name, 'value' => $value]) . static::getAttributes($attrib) . ' />';
			case 'checkbox':
				return static::inputCheckbox($name, $value, $attrib);
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
		return '<input' . static::getAttributes(static::arrayMerge(['type' => 'password', 'id' => $name, 'name' => $name], $attrib)) . ' />';
	}
	
	public static function inputSubmit($name, $value, $attrib = null) {
		return static::input('submit', $name, $value, $attrib);
	}
	
	public static function inputHidden($name, $value, $attrib = null) {
		return static::input('hidden', $name, $value, $attrib);
	}
	
	public static function inputCheckbox($name, $value, $attrib = null) {
		$value = static::checkValue($name, $value);
		$default = ['type' => 'checkbox', 'id' => $name, 'name' => $name];
		if ($value) {
			$default['checked'] = 'checked';
		}
		return '<input' . static::getAttributes($default) . static::getAttributes($attrib) . ' />';
	}
	
	public static function textarea($name, $value, $attrib = null) {
		$value = static::checkValue($name, $value);
		return '<textarea' . static::getAttributes(['id' => $name, 'name' => $name]) . static::getAttributes($attrib) . '>' . filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '</textarea>';
	}
	
	public static function select($name, $value, $values, $attrib = null) {
		$value = static::checkValue($name, $value);
		$return = '<select' . static::getAttributes(static::arrayMerge(['name' => $name, 'id' => $name], $attrib)) . '>';
		foreach($values as $val => $display) {
			$return .= '<option value="' . HTML::filter($val) . '"';
			if ($value == $val) {
				$return .= ' selected="selected"';
			}
			$return .= '>' . HTML::filter($display) . '</option>';
		}
		return $return . '</select>';
	}
	
	public static function getAttributes($attrib) {
		$result = '';
		if ($attrib !== null) {
			foreach ($attrib as $name => $value) {
				$result .= ' ' . $name . '="' . HTML::filter($value) . '"';
			}
		}
		return $result;
	}
	
	private static function arrayMerge($a, $b) {
		if ($b != null) {
			return array_merge($a, $b);
		} else {
			return $a;
		}
	}
	
	public static function checkValue($name, $value) {
		if (!isset(Registry::getInstance()->input)) {
			return $value;
		}
		
		return (isset(Registry::getInstance()->input->{$name})) ? Registry::getInstance()->input->{$name} : $value;
	}
}

?>