<?php
use View\HTML;
use Application\Uri;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php HTML::out($page_title); ?></title>

<link rel="stylesheet" href="<?php echo Uri::getBase() ?>view/default/style.css" type="text/css" media="screen" />

</head>
<body>

<div id="page">

<div id="header">
	<div id="headerimg">
		<h1><a href="<?php HTML::out(Uri::to('/')); ?>"><?php HTML::out($settings->getSiteTitle()); ?></a></h1>
		<div class="description"></div>
	</div>
</div>

<hr />

<div id="content" class="<?php echo ((isset($wideColumn) && $wideColumn === true) ? 'widecolumn' : 'narrowcolumn'); ?>">
