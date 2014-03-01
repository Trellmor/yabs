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

use Models\User;

class Session {
	private static $started = false;

	public static function load() {
		if (static::isSessionActive()) {
			static::start();
			if (isset($_SESSION['user_id'])) {
				$user = User::load($_SESSION['user_id'], true);
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