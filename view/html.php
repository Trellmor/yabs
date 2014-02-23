<?php namespace View;

class HTML {
	public static function filter($var) {
		return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	
	public static function out($var) {
		echo static::filter($var);
	}
}

?>