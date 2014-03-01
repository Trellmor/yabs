<?php

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

//$rendertime = microtime(true);

error_reporting(E_ALL);

$site_path = realpath(dirname(__FILE__));
define('APP_ROOT', $site_path);

require_once APP_ROOT . '/app/init.php';

//echo '<!-- Render time: ' . round((microtime(true) - $rendertime) * 1000) . 'ms -->';

?>