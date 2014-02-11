<?php

define('BR', '<br />');

error_reporting(E_ALL);

$site_path = realpath(dirname(__FILE__));
define('APP_ROOT', $site_path);

require_once APP_ROOT . '/app/init.php';

?>