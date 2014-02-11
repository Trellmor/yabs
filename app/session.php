<?php namespace Application;

use Models\User;

class Session {
	private static $started = false;

	public static function load() {
		if (static::isSessionActive()) {
			static::start();
			if (isset($_SESSION['user_id'])) {
				$user = User::load($_SESSION['user_id']);
				Registry::getInstance()->user = $user;
			}
		}
	}

	public static function start() {
		if (!static::$started) {
			session_start();
			static::$started = true;
		}
	}

	public static function destroy() {
		Registry::getInstance()->user = null;

		if (static::isSessionActive()) {
			$_SESSION = array();

			if (ini_get('session.use_cookies')) {
				$params = session_get_cookie_params();
				setcookie(
						session_name(),
						'',
						time() - 42000,
						$params['path'],
						$params['domain'],
						$params['secure'],
						$params['httponly']
				);
			}

			session_destroy();
		}
	}

	public static function isSessionActive() {
		return isset($_COOKIE[session_name()]) && !empty($_COOKIE[session_name()]);
	}
}

?>