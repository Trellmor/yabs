<?php
use View\Forms;
use Application\Uri;
?>
<h1 class="page-header"><?php echo _('Entries'); ?></h1>
<div class="table-responsive">
  <?php echo Forms::form(Uri::to('admin/entry/delete')); ?>
  <?php echo Forms::input('hidden', 'page', $page); ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php foreach ($entries as $entry): ?>
      <tr>
        <th><?php echo $entry->getId(); ?></th>
        <th><?php echo $entry->getTitle(); ?></th>
        <th><a class="btn btn-default" href="<?php echo Uri::to('admin/entry/' . $entry->getId()); ?>"><span class="glyphicon glyphicon-pencil"></span></a></th>
        <th>
          <button name="entry_id" value="<?php echo $entry->getId(); ?>" class="btn btn-default" type="submit">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </th>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
  </form>
  <ul class="pager">
    <li class="previous <?php echo ($page <= 1) ? 'disabled' : ''; ?>"><a href="<?php echo Uri::to('admin/entry/page/' . ($page - 1)); ?>">&larr; <?php echo _('Newer'); ?></a></li>
    <li class="next <?php echo (count($entries) < 30) ? 'disabled' : ''; ?>"><a href="<?php echo Uri::to('admin/entry/page/' . ($page + 1)); ?>"><?php echo _('Older'); ?> &rarr;</a></li>
  </ul>
  
</div>