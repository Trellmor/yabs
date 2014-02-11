<?php use Application\Uri ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>{S_BLOG_TITLE}</title>

<link rel="stylesheet" href="<?php echo Uri::to('/') ?>view/default/style.css" type="text/css" media="screen" />

</head>
<body>

<div id="page">


<div id="header">
	<div id="headerimg">
		<h1><a href="<?php echo Uri::to('/') ?>">{S_BLOG_TITLE}</a></h1>
		<div class="description">{S_BLOG_SUBTITLE}</div>
	</div>
</div>

<hr />

<div id="content" class="narrowcolumn">
