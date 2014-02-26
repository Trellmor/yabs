<?php
use Application\Uri;
use view\Forms;

$view->load('header');
$view->handleMessages();
?>

<h2><?php echo _('Login'); ?></h2>
<?php echo Forms::form(Uri::to('admin/login')); ?>
	<?php echo _('Username'); ?>: <?php echo Forms::input('text', 'username'); ?><br />
	<?php echo _('Password'); ?>: <?php echo Forms::input('password', 'password'); ?><br />
	<?php echo Forms::input('submit', 'submit', _('Login')); ?>
</form>
<?php
$view->load('footer');
?>