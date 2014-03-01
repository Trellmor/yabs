<?php
use View\HTML;
use View\Forms;
use Application\Uri;

$view->load('header');

//Load the modals
$view->assignVar('modal_id', 'modal-user-delete');
$view->assignVar('modal_title', _('Delete user'));
$view->assignVar('modal_body', _('Do you really want to delete this user?'));
$view->assignVar('modal_button_negative', _('Cancel'));
$view->assignVar('modal_button_positive', _('Delete'));
$view->load('modal');
?>
<h1 class="page-header"><?php echo _('Users'); ?></h1>
<?php $view->handleMessages(); ?>
<div class="table-responsive">
  <?php echo Forms::form(Uri::to('admin/user/delete'), ['id' => 'user-delete']); ?>
  <?php echo Forms::input('hidden', 'user_id', ''); ?>
  </form>
  <table class="table table-striped">
    <thead>
      <tr>
        <th class="col-md-10"><?php echo _('Name'); ?></th>
        <th class="col-md-1 text-center"><?php echo _('Active'); ?></th>
        <th class="col-md-1"></th>
      </tr>
    </thead>
    <tbody>
<?php foreach ($users as $currUser): ?>
      <tr>
        <th><?php HTML::out($currUser->getName()); ?></th>
        <th class="text-center">
          <span class="color-<?php echo ($currUser->isActive()) ? 'success' : 'danger'?>">
            <span class="glyphicon glyphicon-<?php echo ($currUser->isActive()) ? 'ok-circle' : 'remove-circle'; ?>"></span>
          </span>
        </th>
        <th class="text-center">
          <a class="btn btn-default" href="<?php HTML::out(Uri::to('admin/user/' . $currUser->getId())) ?>"><span class="glyphicon glyphicon-pencil"></span></a>
          <button data-id="<?php echo $currUser->getId(); ?>" class="btn btn-default user-delete" type="submit">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </th>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
  
 </div>
 
 <a class="btn btn-default" href="<?php HTML::out(Uri::to('admin/user/new')); ?>">New</a>
<?php $view->load('footer'); ?>