<?php namespace Controllers;

use Application\Registry;
use Application\Route;

/**
 * All routes are registered here
 */
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'index', '/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'index', 'blog/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'index', 'blog/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'entry', 'blog/(.*)/'));

Registry::getInstance()->router->addRoute(Route::post('Controllers\Comment', 'add', 'comment/add/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Login', 'index', 'admin/login/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Login', 'login', 'admin/login/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Login', 'Logout', 'admin/logout/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\AdminController', 'index', 'admin/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Entry', 'index', 'admin/entry/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Entry', 'index', 'admin/entry/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Entry', 'create', 'admin/entry/new/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Entry', 'edit', 'admin/entry/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Entry', 'save', 'admin/entry/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Entry', 'saveNew', 'admin/entry/new/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Entry', 'delete', 'admin/entry/delete/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Settings', 'index', 'admin/settings/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Settings', 'save', 'admin/settings/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Category', 'index', 'admin/category/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Category', 'edit', 'admin/category/new/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Category', 'edit', 'admin/category/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Category', 'save', 'admin/category/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Category', 'save', 'admin/category/new/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Category', 'delete', 'admin/category/delete/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Comment', 'index', 'admin/comment/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Comment', 'index', 'admin/comment/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Comment', 'toggleSpam', 'admin/api/comment/spam/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Comment', 'toggleVisible', 'admin/api/comment/visible/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Comment', 'delete', 'admin/comment/delete/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\User', 'profile', 'admin/profile/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\User', 'saveProfile', 'admin/profile/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\User', 'index', 'admin/user/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\User', 'edit', 'admin/user/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\User', 'edit', 'admin/user/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\User', 'delete', 'admin/user/delete/'));

?>