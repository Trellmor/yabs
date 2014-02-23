<?php
use View\HTML;
use Application\Uri;
?>
<div class="post">
	<h2><a href="<?php echo Uri::to('blog/' . HTML::filter($entry->getUri())); ?>"><?php HTML::out($entry->getTitle()); ?></a></h2>
	<small><?php echo date('l, j. F Y G:H', $entry->getDate()); ?> by <?php HTML::out($entry->getUserName()); ?></small>

	<div class="entry">
		<p>
			<?php echo $entry->getTeaser() . $entry->getContent(); ?>
		</p>
	</div>

	<p class="postmetadata">Posted in <?php HTML::out($entry->getCategoryName()); ?> | {S_ENTRY_FEEDBACKNUMBERS} &#187;</p>
</div>