<?php
use Application\Uri;
use view\Forms;
?>

<h2><?php echo _('Login'); ?></h2>
<form action="<?php echo Uri::to('admin/login'); ?>" method="post">
	<?php echo _('Username'); ?>: <?php echo Forms::form('text', 'username'); ?><br />
	<?php echo _('Password'); ?>: <?php echo Forms::form('password', 'password'); ?><br />
	<?php echo Forms::form('submit', 'submit', _('Login')); ?>
</form>