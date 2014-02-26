<?php namespace DAL;

use Application\Registry;

class MySQL extends DAL {
	static function init() {
		Registry::getInstance()->db->exec('SET SESSION sql_warnings=1');
		Registry::getInstance()->db->exec('SET NAMES utf8');
		Registry::getInstance()->db->exec('SET SESSION sql_mode = "ANSI,TRADITIONAL"');
	}
}

?>