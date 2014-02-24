<?php
use Models\User;
use View\HTML;
use View\Forms;
use Application\Uri;

//Load the modals
$view->assignVar('modal_id', 'modal-category-delete');
$view->assignVar('modal_title', _('Delete category'));
$view->assignVar('modal_body', _('Do you really want to delete this category?'));
$view->assignVar('modal_button_negative', _('Cancel'));
$view->assignVar('modal_button_positive', _('Delete'));
$view->load('modal');
?>
<h1 class="page-header"><?php echo _('Categories'); ?></h1>
<div class="table-responsive">
  <?php echo Forms::form(Uri::to('admin/category/delete'), ['id' => 'category-delete']); ?>
  <?php echo Forms::input('hidden', 'category_id', ''); ?>
  </form>
  <table class="table table-striped">
    <thead>
      <tr>
        <th><?php echo _('Name'); ?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php foreach ($categories as $category): ?>
      <tr>
        <th><?php HTML::out($category->getName()); ?></th>
        <th class="text-right">
          <a class="btn btn-default" href="<?php echo Uri::to('admin/category/' . $category->getId()); ?>"><span class="glyphicon glyphicon-pencil"></span></a>
          <?php if ($user->hasPermission(User::PERM_CATEGORY_DELETE)): ?>
          <button data-id="<?php echo $category->getId(); ?>" class="btn btn-default category_delete" type="submit">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
          <?php endif; ?>
        </th>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<h2 class="sub-header"><?php echo _('New category'); ?></h2>

<?php echo Forms::form(Uri::to('admin/category/new', ['role' => 'form'])); ?>

<div class="form-group">
  <label for="entry_title"><?php echo _('Name'); ?></label>
  <?php echo Forms::input('text', 'category_name', '', ['class' => 'form-control']) ?>
</div>

<div class="form-group">
  <?php echo Forms::input('submit', 'save', _('Save'), ['class' => 'btn btn-default']); ?>
</div>

</form>