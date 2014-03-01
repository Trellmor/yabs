<?php namespace Widgets;

use DAL\Factory as DALFactory;

class LatestComments {
	public static function load($view) {
		$comments = DALFactory::DAL()->Select_LatestComments()->fetchAll(\PDO::FETCH_CLASS, 'Models\Comment');
		if (count($comments) > 0) {
			$view->assignVar('comments', $comments);
			$view->load('widget_latestcomments');
		}
	}
}
