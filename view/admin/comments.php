<?php
use Models\User;
use View\HTML;
use View\Forms;
use Application\Uri;

$view->load('header');

//Load the modals
$view->assignVar('modal_id', 'modal-comment-delete');
$view->assignVar('modal_title', _('Delete comment'));
$view->assignVar('modal_body', _('Do you really want to delete this comment?'));
$view->assignVar('modal_button_negative', _('Cancel'));
$view->assignVar('modal_button_positive', _('Delete'));
$view->load('modal');
?>
 
<div class="modal fade" id="modal-comment-show" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo _('Close'); ?></button>
      </div>
    </div>
  </div>
</div>

<h1 class="page-header"><?php echo _('Comments'); ?></h1>
<?php $view->handleMessages(); ?>
<div class="table-responsive">
  <?php echo Forms::form(Uri::to('admin/comment/delete'), ['id' => 'comment-delete']); ?>
  <?php echo Forms::input('hidden', 'page', $page); ?>
  <?php echo Forms::input('hidden', 'comment_id', ''); ?>
  </form>
  <table class="table table-striped">
    <thead>
      <tr>
        <th class="col-md-5"><?php echo _('Author'); ?></th>
        <th class="col-md-2"><?php echo _('IP'); ?></th>
        <th class="col-md-2"><?php echo _('Date'); ?></th>
        <th class="col-md-1 text-center"><?php echo _('Visible'); ?></th>
        <th class="col-md-1 text-center"><?php echo _('Spam'); ?></th>
        <th class="col-md-1"></th>
      </tr>
    </thead>
    <tbody>
<?php foreach ($comments as $comment): ?>
      <tr>
        <th><?php HTML::out($comment->getAuthor()); ?></th>
        <th><span class="comment-ip" data-toggle="tooltip" data-placement="right" title="<?php HTML::out($comment->getHostname()); ?>"><?php HTML::out($comment->getIP()); ?></span></th>
        <th><?php HTML::out(date('Y-m-d H:i', $comment->getDate())); ?></th>
        <th class="text-center">
          <button data-id="<?php echo $comment->getId(); ?>" class="btn btn-default comment-toggle-visible color-<?php echo ($comment->isVisible()) ? 'success' : 'danger' ?>" type="submit">
            <span class="glyphicon glyphicon-eye-<?php echo ($comment->isVisible()) ? 'open' : 'close'; ?>"></span>
          </button>
        </th>
        <th class="text-center">
          <button data-id="<?php echo $comment->getId(); ?>" class="btn btn-default comment-toggle-spam color-<?php echo ($comment->isSpam()) ? 'danger' : 'success' ?>" type="submit">
            <span class="glyphicon glyphicon-<?php echo ($comment->isSpam()) ? 'fire' : 'ok-circle'; ?>"></span>
          </button>
        </th>
        <th class="text-center">
          <button data-id="<?php echo $comment->getId(); ?>" class="btn btn-default comment-show" type="submit">
            <span class="glyphicon glyphicon-zoom-in"></span>
          </button>
          <button data-id="<?php echo $comment->getId(); ?>" class="btn btn-default comment-delete" type="submit">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </th>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
  <ul class="pager">
    <li class="previous <?php echo ($page <= 1) ? 'disabled' : ''; ?>"><a href="<?php HTML::out(Uri::to('admin/comment/page/' . ($page - 1))); ?>">&larr; <?php echo _('Newer'); ?></a></li>
    <li class="next <?php echo (count($comments) < 15) ? 'disabled' : ''; ?>"><a href="<?php HTML::out(Uri::to('admin/comment/page/' . ($page + 1))); ?>"><?php echo _('Older'); ?> &rarr;</a></li>
  </ul>
  
</div>
<?php $view->load('footer'); ?>