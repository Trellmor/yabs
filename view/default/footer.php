<?php 
use Application\Uri;
use View\HTML;
?>
</div>

<?php if (!isset($wideColumn) || !$wideColumn): ?>
<div id="sidebar">
	<ul>
<?php Widgets\LatestEntry::load($view, 'News'); ?>	
<?php Widgets\Categories::load($view); ?>
<?php Widgets\LatestComments::load($view); ?>		
	</ul>
</div>
<?php endif; ?>

<hr />

<div id="footer">
	<p>
		<!-- feel free to remove this -->
		<?php HTML::out($settings->getSiteTitle()) ?> is proudly powered by 
		<a href="http://yabs.tac-ops.net/">yabs</a> and <a href="http://binarybonsai.com/kubrick/">Kubrick</a> by Michael Heilmann

		<br /><a href="<?php HTML::out(Uri::to('feed')); ?>"><?php echo _('Feed') ?></a> (ATOM 1.0) | <a href="<?php HTML::out(Uri::to('admin')); ?>"><?php echo _('Admin'); ?></a>
	</p>
</div>

</div>

</body>
</html>