<?php namespace Application;

class UriException extends \Exception {};

class Uri {
	private static $base = null;
	
	public static function to($path) {
		if (static::$base == null) {
			static::generateBaseUri();
		}
		
		return rtrim(static::$base . ltrim($path, '/'), '/') . '/';
	}
	
	public static function pathTo($path) {
		return trim($path, '/') . '/';
	}
	
	public static function detectPath() {
		if (isset($_SERVER['REQUEST_URI']) === false) {
			throw new UriException('REQUEST_URI not set');
		}
		
		$uri = static::removeQuery($_SERVER['REQUEST_URI']);
		$uri = urldecode($uri);		
		$uri = static::removeScript($uri);
		
		return rtrim(ltrim($uri, '/'), '/') . '/';
	}
	
	private static function generateBaseUri() {
		$config = Registry::getInstance()->config['uri'];
		
		$base = ($config['scheme'] != null) ? $config['scheme'] : 'http';
		$base .= '://';
		
		$base .= ($config['host'] != null) ? $config['host'] : $_SERVER['HTTP_HOST'];
		
		if ($config['port'] != null) $base .= ':' . $config['port'];
		
		$base .= rtrim('/' . ltrim($config['path'], '/'), '/') . '/';
		
		static::$base = $base;  
	}
	
	private static function removeScript($uri) {
		if (isset($_SERVER['SCRIPT_NAME']) === false) {
			return $uri;
		}
		
		//Normalize
		$uri = '/' . ltrim($uri, '/');
		
		$dir = dirname($_SERVER['SCRIPT_NAME']);
		if (($pos = strpos($uri, $dir)) === 0) {
			$uri = substr($uri, strlen($dir));
		}
		
		$script = basename($_SERVER['SCRIPT_NAME']);
		if (($pos = strpos($uri, $script)) === 1) {
			$uri = substr($uri, strlen($script) + 1);
		}
		
		return $uri;
	}
	
	private static function removeQuery($uri) {
		if (($pos = strpos($uri, '?')) !== false) {
			$uri = substr($uri, 0, $pos);
		}
		return $uri;
	}
}

?>