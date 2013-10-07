<?php

require_once APP_ROOT . '/includes/config.php';
//require_once APP_ROOT . '/application/controller_base.class.php';
require_once APP_ROOT . '/application/Registry.class.php';
require_once APP_ROOT . '/application/QueryBuilder.class.php';
//require_once APP_ROOT . '/application/router.class.php';
//require_once APP_ROOT . '/application/template.class.php';

function __autoload($class_name) {
	$filename = $class_name . '.class.php';
	$filepath = APP_ROOT . '/model/' . $filename;
	
	if (file_exists($filepath)) {
		include_once $filepath;
	} else {
		return false;
	}
}

Registry::getInstance()->db_prefix = $db_prefix;
Registry::getInstance()->db = new PDO($db_dsn, $db_username, $db_passwd, $db_options);
unset($db_username);
unset($db_passwd);

$builder = QueryBuilderFactory::factory();
var_dump($builder->table('config')->where('id = ?', [0])->query(['name', 'value'])->fetchAll());

?>