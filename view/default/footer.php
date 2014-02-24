<?php 
use Application\Uri;

use View\HTML;
?>
</div>

<div id="sidebar">
	<ul>
		<li class="pagenav"><h2>{KUBRICK_MENU_TITLE}</h2>
			<ul>
				<li class="page_item"><a href="{L_MENU_URL}">{S_MENU_NAME}</a></li>
			</ul>
		</li>

		<li><h2>{S_SIDEBAR_TITLE}</h2>
			{S_SIDEBAR_CONTENT}
<!-- use another <ul> for links if you want -->
		</li>			
	</ul>
</div>

<hr />

<div id="footer">
	<p>
		<!-- feel free to remove this -->
		<?php HTML::out($settings->getSiteTitle()) ?> is proudly powered by 
		<a href="http://yabs.tac-ops.net/">yabs</a> and <a href="http://binarybonsai.com/kubrick/">Kubrick</a> by Michael Heilmann

		<br />{S_FEED_ENTRIES} (ATOM 1.0) | <a href="<?php echo Uri::to('admin'); ?>"><?php echo _('Admin'); ?></a>
	</p>
</div>

</div>

</body>
</html>