<?php 
use Application\Uri;
use View\HTML;
use View\Forms;

$view->load('header');
?>
<h1 class="page-header"><?php echo _('Profile'); ?></h1>
<?php $view->handleMessages(); ?>
<?php echo Forms::form(Uri::to('admin/profile/'), ['role' => 'form']); ?>

<div class="form-group">
  <label><?php echo _('Username'); ?></label>
  <p class="form-control-static">
    <?php HTML::out($user->getName()); ?>
  </p>
</div>

<div class="form-group">
  <label for="user_mail"><?php echo _('E-Mail'); ?></label>
  <?php echo Forms::input('text', 'user_mail', $user->getMail(), ['class' => 'form-control']); ?>
</div>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_password_change', false); ?>
    <?php echo ('Change password'); ?>
  </label>
</div>

<div class="form-group">
  <label class="control-label" for="user_password"><?php echo _('Old password'); ?></label>
  <?php echo Forms::input('password', 'user_password', '', ['class' => 'form-control user-passwords', 'disabled']); ?>
</div>

<div class="form-group">
  <label class="control-label" for="user_password_new"><?php echo _('New password'); ?></label>
  <?php echo Forms::input('password', 'user_password_new', '', ['class' => 'form-control user-passwords user-passwords-new', 'disabled']); ?>
</div>

<div class="form-group">
  <label class="control-label" for="user_password_new_confirm"><?php echo _('New password (confirm)'); ?></label>
  <?php echo Forms::input('password', 'user_password_new_confirm', '', ['class' => 'form-control user-passwords user-passwords-new', 'disabled']); ?>
</div>

<div class="form-group">
  <?php echo Forms::input('submit', 'save', _('Save'), ['class' => 'btn btn-default']); ?>
</div>

</form>
<?php $view->load('footer'); ?>