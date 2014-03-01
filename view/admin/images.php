<?php
use View\HTML;
use View\Forms;
use Application\Uri;
use Models\User;

$view->load('header');
?>
<h1 class="page-header"><?php echo _('Images'); ?></h1>
<?php $view->handleMessages(); ?>

<div class="panel panel-default">
<div class="row panel-body">

<div class="col-md-4">
  <?php if ($user->hasPermission(User::PERM_IMAGE_EDIT)): ?>
  <?php echo Forms::form(Uri::to('admin/image/upload'), ['role' => 'form', 'enctype' => 'multipart/form-data']); ?>
    <div class="input-group">
      <span class="input-group-btn">
        <span class="btn btn-primary btn-file">
          <?php echo _('Browse'); ?><input type="file" name="file">
        </span>
      </span>
      <input class="form-control" readonly type="text">
      <span class="input-group-btn">
        <button class="btn btn-default" type="input"><?php echo _('Upload')?></button>
      </span>
    </div>
  </form>
  <?php endif; ?>
</div>
<div class="col-md-3 col-md-offset-5">
  <form action="<?php HTML::out(Uri::to('admin/image/page/' . $page)->param('order', $order)); ?>" method="get" role="form">
    <div class="input-group">
      <?php echo Forms::input('text', 'q', $query, ['class' => 'form-control']); ?>
      <span class="input-group-btn">
        <button class="btn btn-default" type="input">
          <span class="glyphicon glyphicon-search"></span>
        </button>
      </span>
    </div>
  </form>
</div>

</div>
</div>

<div class="row">
<?php for($i = 0; $i < count($images); $i++): ?>

<?php if ($i > 0 && $i % 3 == 0): ?>
</div>
<div class="row">
<?php endif; ?>

<div class="col-md-4">
  <a href="<?php HTML::out(Uri::to('admin/image/' . $images[$i]->getId())); ?>" class="thumbnail">
    <img src="<?php HTML::out($images[$i]->getUri()); ?>" class="img-responsive" />
  </a>
</div>

<?php endfor; ?>
</div>

<ul class="pager">
  <li class="previous <?php echo ($page <= 1) ? 'disabled' : ''; ?>"><a href="<?php HTML::out(Uri::to('admin/image/page/' . ($page - 1))->param('order', $order)->param('q', $query)); ?>">&larr; <?php echo _('Previous'); ?></a></li>
  <li class="next <?php echo (count($images) < 9) ? 'disabled' : ''; ?>"><a href="<?php HTML::out(Uri::to('admin/image/page/' . ($page + 1))->param('order', $order)->param('q', $query)); ?>"><?php echo _('Next'); ?> &rarr;</a></li>
</ul>

<?php $view->load('footer'); ?>