<?php
use View\HTML;
use View\Forms;
use Application\Uri;
use Models\User;

$view->load('header');
?>
<h1 class="page-header"><?php echo _('User'); ?></h1>
<?php $view->handleMessages(); ?>
<?php echo Forms::form(Uri::to('admin/user/' . (($editUser->getId() >= 0) ? $editUser->getId() : 'new'), ['role' => 'form'])); ?>

<div class="form-group">
  <label for="user_name"><?php echo _('Name'); ?></label>
  <?php echo Forms::input('text', 'user_name', $editUser->getName(), ['class' => 'form-control']); ?>
</div>

<div class="form-group">
  <label for="user_mail"><?php echo _('E-Mail'); ?></label>
  <?php echo Forms::input('text', 'user_mail', $editUser->getMail(), ['class' => 'form-control']); ?>
</div>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_active', $editUser->isActive()); ?>
    <?php echo ('Active'); ?>
  </label>
</div>

<h2 class="sub-header"><?php echo _('Password'); ?></h2>

<?php if ($editUser->getId() >= 0): ?>
<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_password_change', false); ?>
    <?php echo ('Change password'); ?>
  </label>
</div>
<?php endif; ?>

<div class="form-group">
  <label class="control-label" for="user_password"><?php echo _('Password'); ?></label>
  <?php echo Forms::input('password', 'user_password', '', ['class' => 'form-control user-passwords', (($editUser->getId() >= 0) ? 'disabled': '')]); ?>
</div>

<h2 class="sub-header"><?php echo _('Permissions'); ?></h2>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_permission_entry', $editUser->hasPermission(User::PERM_ENTRY)); ?>
    <?php echo ('Create, edit and delete entries'); ?>
  </label>
</div>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_permission_entry_all', $editUser->hasPermission(User::PERM_ENTRY_ALL)); ?>
    <?php echo ('Manage other users entries'); ?>
  </label>
</div>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_permission_category', $editUser->hasPermission(User::PERM_CATEGORY)); ?>
    <?php echo ('Manage categories'); ?>
  </label>
</div>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_permission_comment', $editUser->hasPermission(User::PERM_COMMENT)); ?>
    <?php echo ('Manage comments'); ?>
  </label>
</div>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_permission_settings', $editUser->hasPermission(User::PERM_SETTINGS)); ?>
    <?php echo ('Edit settings'); ?>
  </label>
</div>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'user_permission_user', $editUser->hasPermission(User::PERM_USER)); ?>
    <?php echo ('Create, edit and delete users'); ?>
  </label>
</div>

<div class="form-group">
  <?php echo Forms::input('submit', 'save', _('Save'), ['class' => 'btn btn-default']); ?>
</div>

</form>
<?php $view->load('footer'); ?>