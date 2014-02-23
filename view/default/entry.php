<?php

use Application\Uri;

?>
<div class="post">
	<h2><a href="<?php echo Uri::to('blog/' . urlencode($entry->getUri())); ?>"><?php echo $entry->getTitle(); ?></a></h2>
	<small><?php echo date('l, j. F Y G:H', $entry->getDate()); ?> by <?php echo $entry->getUserName(); ?></small>

	<div class="entry">
		<p>
			<?php echo $entry->getTeaser() . $entry->getContent(); ?>
		</p>
	</div>

	<p class="postmetadata">Posted in <?php echo $entry->getCategoryName(); ?> | {S_ENTRY_FEEDBACKNUMBERS} &#187;</p>
</div>