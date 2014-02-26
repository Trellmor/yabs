<?php
use View\HTML;
use View\Forms;
use Application\Uri;

//Load the modals
$view->assignVar('modal_id', 'modal-entry-delete');
$view->assignVar('modal_title', _('Delete entry'));
$view->assignVar('modal_body', _('Do you really want to delete this entry?'));
$view->assignVar('modal_button_negative', _('Cancel'));
$view->assignVar('modal_button_positive', _('Delete'));
$view->load('modal');
?>
<h1 class="page-header"><?php echo _('Entries'); ?></h1>
<div class="table-responsive">
  <?php echo Forms::form(Uri::to('admin/entry/delete'), ['id' => 'entry-delete']); ?>
  <?php echo Forms::input('hidden', 'page', $page); ?>
  <?php echo Forms::input('hidden', 'entry_id', ''); ?>
  </form>
  <table class="table table-striped">
    <thead>
      <tr>
        <th class="col-md-1">#</th>
        <th class="col-md-9"><?php echo _('Title'); ?></th>
        <th class="col-md-1 text-center"><?php echo _('Published'); ?></th>
        <th class="col-md-1"></th>
      </tr>
    </thead>
    <tbody>
<?php foreach ($entries as $entry): ?>
      <tr>
        <th><?php echo $entry->getId(); ?></th>
        <th><?php HTML::out($entry->getTitle()); ?></th>
        <th class="text-center">
          <span class="color-<?php echo ($entry->isVisible()) ? 'success' : 'danger'?>">
            <span class="glyphicon glyphicon-eye-<?php echo ($entry->isVisible()) ? 'open' : 'close'; ?>"></span>
          </span>
        </th>
        <th class="text-center">
          <a class="btn btn-default" href="<?php echo Uri::to('admin/entry/' . $entry->getId()); ?>"><span class="glyphicon glyphicon-pencil"></span></a>
          <button data-id="<?php echo $entry->getId(); ?>" class="btn btn-default entry-delete" type="submit">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </th>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
  <ul class="pager">
    <li class="previous <?php echo ($page <= 1) ? 'disabled' : ''; ?>"><a href="<?php echo Uri::to('admin/entry/page/' . ($page - 1)); ?>">&larr; <?php echo _('Newer'); ?></a></li>
    <li class="next <?php echo (count($entries) < 15) ? 'disabled' : ''; ?>"><a href="<?php echo Uri::to('admin/entry/page/' . ($page + 1)); ?>"><?php echo _('Older'); ?> &rarr;</a></li>
  </ul>
  
</div>