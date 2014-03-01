<?php namespace Application;

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

class Autoloader {
	protected $root = '/';
	protected $prefixes = array();
	
	public function __construct($root) {		
		$this->root = rtrim($root, '/') . '/';
	}
		
	public function addNamespace($prefix, $dir, $prepend = false) {
		$prefix = trim($prefix, '\\') . '\\';
		
		if (isset($this->prefixes[$prefix]) === false) {
			$this->prefixes[$prefix] = array();
		}
		
		$dir = rtrim($dir, '/') . '/';
		if ($prepend) {
			array_unshift($this->prefixes[$prefix], $dir);	
		} else {
			$this->prefixes[$prefix][] = $dir; 
		}
		
	}
	
	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}
	
	public function loadClass($class) {
		$prefix = $class;
				
		while (false !== $pos = strrpos($prefix, '\\')) {
			$prefix = substr($class, 0, $pos + 1);
			
			$relative_class = substr($class, $pos + 1);
			
			$mapped_file = $this->loadMappedFile($prefix, $relative_class);
			if ($mapped_file) {
				return $mapped_file;
			}
			
			$prefix = rtrim($prefix, '\\');
		}
		
		$mapped_file = $this->loadMappedFile('\\', $class);
		if ($mapped_file) {
			return $mapped_file;
		}
		
		return false;
	}
	
	protected function loadMappedFile($prefix, $relative_class) {
		if (isset($this->prefixes[$prefix]) === false) {
			return false;
		}
		
		foreach ($this->prefixes[$prefix] as $dir) {		
			$file = $dir . str_replace('\\', '/', strtolower($relative_class)) . '.php';
				
			if ($this->requireFile($file)) {
				return $file;
			}
		}
		
		return false;
	}
	
	protected function requireFile($file) {
		if (file_exists($file)) {
			require $file;
			return true;
		}
		return false;
	}
}
