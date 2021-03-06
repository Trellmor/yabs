<?php 
use View\HTML;
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

    <link href="<?php echo Uri::getBase(); ?>view/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">    
    <link href="<?php echo Uri::getBase(); ?>view/libs/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="<?php echo Uri::getBase(); ?>view/admin/admin.css" rel="stylesheet">
  </head>

  <body data-csrf="<? HTML::out($csrf->getToken()); ?>" data-base-uri="<?php HTML::out(Uri::to('')); ?>">

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only"><?php echo _('Toggle navigation')?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php HTML::out(Uri::to('/')); ?>"><?php HTML::out($settings->getSiteTitle()); ?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php HTML::out(Uri::to('admin')); ?>"><?php echo _('Dashboard'); ?></a></li>
            <?php if ($user->hasPermission(User::PERM_SETTINGS)): ?>
            <li><a href="<?php HTML::out(Uri::to('admin/settings')); ?>"><?php echo _('Settings'); ?></a></li>
            <?php endif; ?>
            <li><a href="<?php HTML::out(Uri::to('admin/profile/')); ?>"><?php echo _('Profile'); ?></a></li>
            <li><a href="<?php HTML::out(Uri::to('admin/logout/')); ?>"><?php echo _('Logout'); ?></a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
                   
            <?php if ($user->hasPermission(User::PERM_ENTRY)): ?> 
            <li><a href="<?php HTML::out(Uri::to('admin/entry/new')); ?>"><?php echo _('New entry'); ?></a></li>
            <li><a href="<?php HTML::out(Uri::to('admin/entry')); ?>"><?php echo _('Entries'); ?></a></li>
            <?php endif; ?>
            
            <?php if ($user->hasPermission(User::PERM_CATEGORY)): ?>
          	<li><a href="<?php HTML::out(Uri::to('admin/category')); ?>"><?php echo _('Categories'); ?></a></li>
          	<?php endif; ?>
          	
          	<?php if ($user->hasPermission(User::PERM_IMAGE)): ?>
          	<li><a href="<?php HTML::out(Uri::to('admin/image')); ?>"><?php echo _('Images'); ?></a></li>
          	<?php endif; ?>
          	
            <!--<li class="active"><a href="#">Reports</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="#">Export</a></li>-->
          </ul>
                    
          <ul class="nav nav-sidebar">          
            <?php if ($user->hasPermission(User::PERM_COMMENT)): ?>
          	<li><a href="<?php HTML::out(Uri::to('admin/comment')); ?>"><?php echo _('Comments'); ?></a></li>
          	<li><a href="<?php HTML::out(Uri::to('admin/comment/spam')); ?>"><?php echo _('Spam'); ?></a></li>
          	<?php endif; ?>
          </ul>
                
          <ul class="nav nav-sidebar">          
            <?php if ($user->hasPermission(User::PERM_USER)): ?>
          	<li><a href="<?php HTML::out(Uri::to('admin/user')); ?>"><?php echo _('User'); ?></a></li>
          	<?php endif; ?>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
