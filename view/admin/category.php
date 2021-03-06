<?php
use View\Forms;
use Application\Uri;

$view->load('header');
?>
<h1 class="page-header"><?php _('Category'); ?></h1>
<?php $view->handleMessages(); ?>
<?php echo Forms::form(Uri::to('admin/category/' . (($category->getId() >= 0) ? $category->getId() : 'new'), ['role' => 'form'])); ?>

<div class="form-group">
  <label for="entry_title"><?php echo _('Name'); ?></label>
  <?php echo Forms::input('text', 'category_name', $category->getName(), ['class' => 'form-control']) ?>
</div>

<div class="form-group">
  <?php echo Forms::input('submit', 'save', _('Save'), ['class' => 'btn btn-default']); ?>
</div>

</form>
<?php $view->load('footer'); ?>