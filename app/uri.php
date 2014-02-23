<?php namespace Application;

use Application\Exceptions\UriException;

class Uri {
	private static $base = null;
	
	private $path;
	private $params = [];
	
	/**
	 * Create new Uri instance with specific path
	 * 
	 * @param string $path
	 * @return Uri instance
	 */
	public static function to($path) {
		$uri = new Uri();
		return $uri->path($path);
	}
	
	/**
	 * Create new URI instance to the current page
	 * 
	 * @return Uri instance
	 */
	public static function currentPage() {
		$uri = static::to(static::detectPath());
		foreach ($_GET as $k => $v) {
			$uri->param($k, $v);
		}
		
		return $uri;
	}
	
	/**
	 * Set the URI path
	 * 
	 * @param string $path
	 * @return Uri instance
	 */
	public function path($path) {
		$this->path = $path;
		return $this;
	}
	
	/**
	 * Get the path component
	 * 
	 * @return string Path
	 */
	public function getPath() {
		return Uri::pathTo($this->path);
	}
	
	/**
	 * Set a query param
	 * 
	 * Name and value will be url encoded with rawurlencode 
	 * 
	 * @param string $param Param name
	 * @param string $value
	 */
	public function param($param, $value) {
		$param = rawurlencode($param);
		$value = rawurlencode($value);
		$this->params[$param] = $value;
		return $this;
	}
	
	/**
	 * Cast to string
	 * 
	 * When casting Uri to string, & in the query string will be encoded to 
	 * &amp; Use the dedicated build($encode_amp) function to control if & will
	 * be converted or not. 
	 * 
	 * @return string Uri
	 */
	public function __toString() {
		return $this->build(false);
	}
	
	/**
	 * Convert to string
	 * 
	 * If $encode_amp is true, & in the query string will be encoded as &amp;
	 * 
	 * @param bool $encode_amp Encode & to &amp;
	 * @return string
	 */
	public function build($encode_amp = true) {
		if (static::$base == null) {
			static::generateBaseUri();
		}
		
		$uri = rtrim(static::$base . ltrim($this->path, '/'), '/') . '/';
		if (count($this->params) > 0) {
			$uri .= '?';
			$amp = ($encode_amp) ? '&amp;' : '&';
			$query_string = '';
			foreach ($this->params as $param => $value) {
				if ($query_string != '') {
					$query_string .= $amp;
				}
				$query_string .= $param . '=' . $value;
			}
			$uri .= $query_string;
		}
		return $uri;
	}
	
	/**
	 * Generate a routable path 
	 * 
	 * @param string $path
	 * @return string Formatted path
	 */
	public static function pathTo($path) {
		return trim($path, '/') . '/';
	}
	
	/**
	 * Detect the current requested path
	 * 
	 * @throws UriException
	 * @return string
	 */
	public static function detectPath() {
		if (isset($_SERVER['REQUEST_URI']) === false) {
			throw new UriException('REQUEST_URI not set');
		}
		
		return static::detectPathFrom($_SERVER['REQUEST_URI']);
	}
	
	private static function detectPathFrom($uri) {
		$uri = static::removeQuery($uri);
		$uri = urldecode($uri);		
		$uri = static::removeScript($uri);
		
		return rtrim(ltrim($uri, '/'), '/') . '/';
	}
	
	/**
	 * Parse an URI string
	 * 
	 * @param string $uri
	 * @return Uri instance
	 */
	public static function parse($uri) {
		$uri = static::to(static::detectPathFrom(parse_url($uri, PHP_URL_PATH)));
		
		$query = array();
		parse_str(parse_url($uri, PHP_URL_QUERY), $query);
		foreach ($query as $k => $v) {
			$uri->param($k, $v);
		}
		return $uri;
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