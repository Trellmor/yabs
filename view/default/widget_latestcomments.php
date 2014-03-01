<?php 
use Application\Uri;
use View\HTML;
?>
<li>
  <h2><?php echo _('Comments'); ?></h2>
  <?php foreach ($comments as $comment): ?>
  <p>
    <?php echo _('By'); ?> <strong><?php echo HTML::wordwrap($comment->getAuthor()); ?></strong><br />
    <?php echo HTML::wordwrap(substr(HTML::strip($comment->getText()), 0, 140)); ?><br />
    <a href="<?php HTML::out(Uri::to('blog/' . $comment->getEntryUri())); ?>"><?php HTML::out($comment->getEntryTitle()); ?></a>
  </p>
  <?php endforeach; ?>
</li>