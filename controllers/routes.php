<?php namespace Controllers;

use Application\Registry;
use Application\Route;

/**
 * All routes are registered here
 */
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Blog', 'index', '/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Blog', 'index', 'blog/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Blog', 'entry', 'blog/(.*)/'));

Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Comment', 'add', 'comment/add/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Login', 'index', 'admin/login/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Login', 'login', 'admin/login/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Login', 'Logout', 'admin/logout/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\AdminController', 'index', 'admin/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Entry', 'index', 'admin/entry/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Entry', 'index', 'admin/entry/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Entry', 'create', 'admin/entry/new'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Entry', 'edit', 'admin/entry/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Entry', 'save', 'admin/entry/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Entry', 'saveNew', 'admin/entry/new/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Entry', 'delete', 'admin/entry/delete/'));

?>