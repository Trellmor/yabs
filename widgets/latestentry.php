<?php namespace Widgets;

use View\View;
use Models\Entry;

class LatestEntry {
	public static function load(View $view, $category = null) {
		if ($category != null) {
			$entries = Entry::getVisibleEntriesForCategory($category, 1);
		} else {
			$entries = Entry::getVisibleEntries(1);
		}
		
		if ($entries !== false && count($entries) > 0) {
			$view->assignVar('entry', $entries[0]);
			$view->load('widget_latestentry');
		}
	}
}