<?php
use View\HTML;
use Application\Uri;
$view->assignVar('wideColumn', true);
$view->load('header');
?>
<div class="post">
	<h2><a href="<?php HTML::out(Uri::to('blog/' . $entry->getUri())); ?>"><?php HTML::out($entry->getTitle()); ?></a></h2>
	<small><?php HTML::out(date($settings->getDateTimeFormat(), $entry->getDate())); ?> by <?php HTML::out($entry->getUserName()); ?></small>

	<div class="entry">
		<?php echo $entry->getTeaser() . $entry->getContent(); ?>
	</div>
	<p class="postmetadata"><?php echo _('Posted in'); ?> <?php HTML::out($entry->getCategoryName()); ?> | <a href="<?php HTML::out(Uri::to('blog/' . $entry->getUri()) . '#comments'); ?>"><?php echo $entry->getCommentCount(); ?> <?php echo ngettext('Comment', 'Comments', $entry->getcommentCount()) ?> &#187;</a></p>
</div>
<?php 
$view->load('comments');
$view->load('footer');
?>