<?php
use View\HTML;
use Application\Uri;
?>
<h4><?php echo _('Name'); ?></h4>
<p><?php HTML::out($comment->getAuthor()); ?></p>

<h4><?php echo _('E-Mail'); ?></h4>
<p><a href="mailto:<?php HTML::out($comment->getMail()); ?>"><?php HTML::out($comment->getMail()); ?></a></p>

<?php if ($comment->getUrl() != null): ?>
<h4><?php echo _('Homepage'); ?></h4>
<p><a href="<?php HTML::out($comment->getUrl()); ?>"><?php HTML::out($comment->getUrl()); ?></a></p>
<?php endif; ?>

<h4><?php echo _('Date'); ?></h4>
<p><?php HTML::out(date($settings->getDateTimeFormat(), $comment->getDate())); ?></p>

<h4><?php echo _('IP'); ?></h4>
<p><?php HTML::out($comment->getIP()); ?></p>

<h4><?php echo _('Hostname'); ?></h4>
<p><?php HTML::out($comment->getHostname()); ?></p>

<h4><?php echo _('Entry'); ?></h4>
<p><a href="<?php HTML::out(Uri::to('blog/' . $comment->getEntryUri()) . '#com' . $comment->getId()); ?>"><?php HTML::out($comment->getEntryTitle()); ?></a></p>

<h4><?php echo _('Content'); ?></h4>
<?php echo $comment->getText(); ?>