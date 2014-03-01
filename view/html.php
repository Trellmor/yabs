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

class HTML {
	public static function filter($var) {
		return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	
	public static function out($var) {
		echo static::filter($var);
	}
	
	public static function strip($var) {
		return filter_var($var, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	}
	
	public static function wordwrap($var) {
		$var = explode(' ', static::strip($var));
		$new_string = '';
		foreach ($var as $v) {
			if (strlen($v) > 15) {
				$v = wordwrap($v, 15, '<wbr />', true);
			}
			$new_string .= $v . ' ';
		}
		return $new_string;
	}
}

?>