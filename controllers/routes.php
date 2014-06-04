<?php namespace Controllers;

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

use Application\Registry;
use Application\Route;

/**
 * All routes are registered here
 */
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'index', '/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'index', 'blog/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'index', 'blog/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'entry', 'blog/([^/]+)/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'category', 'category/([^/]+)/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'category', 'category/([^/]+)/page/([0-9]+)/'));

Registry::getInstance()->router->addRoute(Route::post('Controllers\Comment', 'add', 'comment/add/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Blog', 'feed', 'feed/'));

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
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Comment', 'comment', 'admin/comment/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Comment', 'toggleSpam', 'admin/api/comment/spam/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Comment', 'toggleVisible', 'admin/api/comment/visible/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Comment', 'delete', 'admin/comment/delete/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Comment', 'indexSpam', 'admin/comment/spam/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Comment', 'indexSpam', 'admin/comment/spam/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Comment', 'deleteSpam', 'admin/comment/spam//delete/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\User', 'profile', 'admin/profile/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\User', 'saveProfile', 'admin/profile/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\User', 'index', 'admin/user/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\User', 'edit', 'admin/user/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\User', 'save', 'admin/user/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\User', 'delete', 'admin/user/delete/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\User', 'edit', 'admin/user/new/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\User', 'save', 'admin/user/new/'));

Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Image', 'index', 'admin/image/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Image', 'index', 'admin/image/page/([0-9]+)/'));
Registry::getInstance()->router->addRoute(Route::post('Controllers\Admin\Image', 'upload', 'admin/image/upload/'));
Registry::getInstance()->router->addRoute(Route::get('Controllers\Admin\Image', 'image', 'admin/image/([0-9]+)/'));

?>