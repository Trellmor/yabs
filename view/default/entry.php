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
		<p>
			<?php echo $entry->getTeaser() . $entry->getContent(); ?>
		</p>
	</div>

	<p class="postmetadata">Posted in <?php HTML::out($entry->getCategoryName()); ?> | {S_ENTRY_FEEDBACKNUMBERS} &#187;</p>
</div>
<?php 
$view->load('comments');
$view->load('footer');
?>