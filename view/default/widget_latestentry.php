<?php 
use Application\Uri;
use View\HTML;
?>
<li>
  <h2><?php HTML::out($entry->getCategoryName()) ?></h2>
  <p><a href="<?php HTML::out(Uri::to('blog/' . $entry->getUri())); ?>"><?php HTML::out($entry->getTitle()); ?></a></p>
  <p><?php HTML::out(HTML::strip($entry->getTeaser())); ?></p>
  <p><a href="<?php HTML::out(Uri::to('blog/' . $entry->getUri())); ?>"><?php echo _('Read more'); ?> &raquo;</a></p>
</li>