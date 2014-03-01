<?php namespace Widgets;

use View\View;
use Models\Category as CategoryModel;

class Categories {
	public static function load(View $view) {
		$categories = CategoryModel::getCategories();
		$view->assignVar('categories', $categories);
		$view->load('widget_categories');
	}
}