<?php
use Application\Uri;
use view\Forms;

$view->load('header');
$view->handleMessages();
?>

<h2><?php echo _('Login'); ?></h2>
<?php echo Forms::form(Uri::to('admin/login'), ['id' => 'loginform']); ?>
	<p>
		<?php echo Forms::input('text', 'user_name'); ?>
		<label for="user_name"><?php echo _('Username'); ?></label>
	</p>
	
	<p>
		<?php echo Forms::input('password', 'user_password'); ?>
		<label for="user_password"><?php echo _('Password'); ?></label>
	</p>
	
	<p>
		<?php echo Forms::input('checkbox', 'user_remember', false, ['class' => 'checkbox']); ?>
		<label for="user_remember"><?php echo _('Remember login'); ?></label>
	</p>
	<?php echo Forms::input('submit', 'submit', _('Login')); ?>
</form>
<?php
$view->load('footer');
?>