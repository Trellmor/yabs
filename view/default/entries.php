<?php

use Application\Uri;

foreach ($entries as $entry): ?>
<div class="post">
	<h2><a href="<?php echo Uri::to('blog/' . urlencode($entry->getUri())); ?>"><?php echo $entry->getTitle(); ?></a></h2>
	<small><?php echo date('l, j. F Y G:H', $entry->getDate()); ?> by <?php echo $entry->getUserName(); ?></small>

	<div class="entry">
		<p>
			<?php if ($entry->hasTeaser()): ?>
				<?php echo $entry->getTeaser(); ?>
				<br /><br />
				<a href="<?php echo URI::to('blog/' . urlencode($entry->getUri())); ?>"><?php echo _('Read more'); ?></a>
			<?php else: ?>
				<?php echo $entry->getContent(); ?>
			<?php endif; ?>
		</p>
	</div>

	<p class="postmetadata">Posted in {S_ENTRY_CATEGORY_NAME} | {S_ENTRY_FEEDBACKNUMBERS} &#187;</p>
</div>
<?php endforeach; ?>
			
<div class="navigation">
	<div class="alignleft"><?php if ($page > 1): ?><a href="<?php echo $page_prev; ?>">Newer</a> <?php endif; ?></div>
	<div class="alignright"><?php if (count($entries) > 0): ?><a href="<?php echo $page_next; ?>">Older</a> <?php endif; ?></div>
</div>