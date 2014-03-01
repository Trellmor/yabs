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

if (version_compare(PHP_VERSION, '5.4') < 0) {
	die('PHP >= 5.4 required');
}

/**
 * Unregister globals
 */
if (ini_get('register_globals')) {
	$sg = array($_REQUEST, $_SERVER, $_FILES);
	
	foreach ($sg as $global) {
		foreach (array_keys($global) as $key) {
			unset(${$key});
		}
	}
}

/**
 * Remove magic quotes
 */
if (get_magic_quotes_gpc()) {
	$gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	
	array_walk_recursive($gpc, function(&$value) {
		$value = stripslashes($value);
	});
}

/**
 * Autoloader
 */
require_once APP_ROOT . '/app/autoloader.php';

/**
 * Load application config
 */
require_once APP_ROOT . '/app/config.php';

/**
 * Initialize autoloader
 */
$autoloader = new Autoloader(APP_ROOT);
$autoloader->addNamespace('\\', APP_ROOT);
$autoloader->addNamespace('Application\\', APP_ROOT . '/app');
$autoloader->addNamespace('DAL', APP_ROOT . '/DAL');
$autoloader->register();
Registry::getInstance()->autoloader = $autoloader;

/**
 * Initialize database connection
 */
$config['database']['options'] += [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
Registry::getInstance()->db = new \PDO($config['database']['dsn'], $config['database']['username'], 
		$config['database']['password'], $config['database']['options']);
unset($config['database']['username']);
unset($config['database']['password']);
\DAL\Factory::DAL()->init();


Registry::getInstance()->config = $config;
Registry::getInstance()->settings = \Models\Settings::load();
Registry::getInstance()->template = Registry::getInstance()->settings->getTemplate();

/**
 * Initialize routing
 */
Registry::getInstance()->router = new Router();
require_once APP_ROOT . '/controllers/routes.php';

/**
 * Internationalization
 */
setlocale(LC_ALL, Registry::getInstance()->settings->getLanguage() . '.utf8');
bindtextdomain('default', APP_ROOT . '/locale');
bind_textdomain_codeset('default', 'UTF-8');
textdomain('default');

/**
 * Session
 */
Session::start();
Input::restore();

/**
 * Route URL
 */
if (!Registry::getInstance()->router->route($_SERVER['REQUEST_METHOD'], Uri::detectPath())) {
	//No valid route found
	http_response_code(404);
	echo '<h1>Page not found.</h1>';
}

?>