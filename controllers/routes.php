<?php namespace Controllers;

use Application\Registry;
use Application\Route;

/**
 * All routes are registered here
 */
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Blog', 'index', '/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Blog', 'index', 'blog/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Blog', 'index', 'blog/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Blog', 'entry', 'blog/(.*)/'));

Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Comment', 'add', 'comment/add/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Login', 'index', 'admin/login/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Login', 'login', 'admin/login/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Login', 'Logout', 'admin/logout/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\AdminController', 'index', 'admin/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Entry', 'index', 'admin/entry/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Entry', 'index', 'admin/entry/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Entry', 'create', 'admin/entry/new/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Entry', 'edit', 'admin/entry/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Entry', 'save', 'admin/entry/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Entry', 'saveNew', 'admin/entry/new/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Entry', 'delete', 'admin/entry/delete/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Settings', 'index', 'admin/settings/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Settings', 'save', 'admin/settings/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Category', 'index', 'admin/category/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Category', 'edit', 'admin/category/new/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Category', 'edit', 'admin/category/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Category', 'save', 'admin/category/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Category', 'save', 'admin/category/new/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Category', 'delete', 'admin/category/delete/'));

Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Comment', 'index', 'admin/comment/'));
Registry::getInstance()->router->addRoute(new Route('GET', 'Controllers\Admin\Comment', 'index', 'admin/comment/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Comment', 'toggleSpam', 'admin/api/comment/spam/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Comment', 'toggleVisible', 'admin/api/comment/visible/'));
Registry::getInstance()->router->addRoute(new Route('POST', 'Controllers\Admin\Comment', 'delete', 'admin/comment/delete/'));

?>