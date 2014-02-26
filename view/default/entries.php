<?php
use View\HTML;
use Application\Uri;
$view->load('header');
?>

<?php foreach ($entries as $entry): ?>
<div class="post">
	<h2><a href="<?php echo Uri::to('blog/' . HTML::filter($entry->getUri())); ?>"><?php HTML::out($entry->getTitle()); ?></a></h2>
	<small><?php Html::out(date($settings->getDateTimeFormat(), $entry->getDate())); ?> by <?php HTML::out($entry->getUserName()); ?></small>

	<div class="entry">
		<p>
			<?php if ($entry->hasTeaser()): ?>
				<?php echo $entry->getTeaser(); ?>
				<br /><br />
				<a href="<?php echo URI::to('blog/' . HTML::filter($entry->getUri())); ?>"><?php echo _('Read more'); ?></a>
			<?php else: ?>
				<?php echo $entry->getContent(); ?>
			<?php endif; ?>
		</p>
	</div>

	<p class="postmetadata">Posted in <?php HTML::out($entry->getCategoryName()); ?> | {S_ENTRY_FEEDBACKNUMBERS} &#187;</p>
</div>
<?php endforeach; ?>
			
<div class="navigation">
	<div class="alignleft"><?php if ($page > 1): ?><a href="<?php echo $page_prev; ?>"><?php echo _('Newer'); ?></a> <?php endif; ?></div>
	<div class="alignright"><?php if (count($entries) == $settings->getEntriesPerPage()): ?><a href="<?php echo $page_next; ?>"><?php echo _('Older'); ?></a> <?php endif; ?></div>
</div>
<?php $view->load('footer'); ?>