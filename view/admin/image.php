<?php 
use View\HTML;
use View\Forms;

$view->load('header');
?>
<h1 class="page-header"><?php HTML::out($image->getName()); ?></h1>
<?php $view->handleMessages(); ?>

<div class="row">
  <div class="thumbnail">
    <img src="<?php HTML::out($image->getUri()); ?>" class="img-responsive" />
    <div class="caption">
      <div class="form-group">
        <label for="image_uri"><?php echo _('URI'); ?></label>
        <?php echo Forms::input('text', 'image_uri', $image->getUri(), ['class' => 'form-control', 'readonly']); ?>
      </div>
    </div>
  </div>
</div>
<?php $view->load('footer'); ?>