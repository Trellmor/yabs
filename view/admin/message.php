<div class="alert <?php echo $message->getCSSLevel(); ?>">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <?php echo nl2br($message->getMessage()); ?>
</div>