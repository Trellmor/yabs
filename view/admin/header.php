<?php 
use Application\Uri;
use Models\User;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title><?php echo _('yabs Dashboard'); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo Uri::to('/'); ?>/view/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo Uri::to('/'); ?>/view/admin/admin.css" rel="stylesheet">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo Uri::to('/'); ?>">Project name</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo Uri::to('admin'); ?>"><?php echo _('Dashboard'); ?></a></li>
            <?php if ($user->hasPermission(User::PERM_SETTINGS)): ?>
            <li><a href="<?php echo Uri::to('admin/settings'); ?>"><?php echo _('Settings'); ?></a></li>
            <?php endif; ?>
            <li><a href="<?php echo Uri::to('admin/user/' . $user->getId()); ?>"><?php echo _('Profile'); ?></a></li>
            <li><a href="<?php echo Uri::to('admin/logout/'); ?>"><?php echo _('Logout'); ?></a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="<?php echo Uri::to('admin/entry/new'); ?>"><?php echo('New entry'); ?></a></li>
            <li><a href="<?php echo Uri::to('admin/entry'); ?>"><?php echo('Entries'); ?></a></li>
            <!--<li class="active"><a href="#">Reports</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="#">Export</a></li>-->
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
