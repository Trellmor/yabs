<?php
use View\HTML;
use View\Forms;
use Application\Uri;
?>

	<h3 id="comments"><?php echo _('Comments')?></h3> 

	<ol class="commentlist">
<?php $i = 0; ?>
<?php foreach ($comments as $comment): ?>
		<li class="<?php echo ($i % 2 == 0) ? 'alt' : ''; $i++; ?>" id="com<?php echo $comment->getId(); ?>">

			<cite><?php HTML::out($comment->getAuthor()); ?></cite>:
			<br />

			<small class="commentmetadata"><a name="com<?php echo $comment->getId(); ?>" href="#com<?php echo $comment->getId(); ?>"><?php echo date('d.m.Y H:i', $comment->getDate()); ?></a></small>

			<p><?php echo $comment->getText(); ?></p>

		</li>
<?php endforeach; ?>
	</ol>
	
<p><br /></p>
<?php $view->handleMessages(); ?>
<h3 id="respond"><a name="add"></a><?php echo _('New comment'); ?></h3>

<?php echo Forms::form(Uri::to('comment/add/')->param('entry_id', $entry->getId())->param('foo', 'bar'), ['id' => 'commentform']); ?>
<p><?php echo Forms::input('text', 'comment_author', $commentAuthor->getName()); ?> <label for="comment_author"><small><?php echo _('Name'); ?></small></label></p>
<p><?php echo Forms::input('text', 'comment_mail', $commentAuthor->getMail()); ?> <label for="comment_mail"><small><?php echo _('E-Mail'); ?></small></label></p>
<p><?php echo Forms::input('text', 'comment_url', $commentAuthor->getUrl()); ?> <label for="comment_url"><small><?php echo _('Homepage'); ?></small></label></p>
<p><?php echo Forms::input('checkbox', 'comment_remember', $commentAuthor->isRemember(), ['class' => 'checkbox']); ?><label for="comment_remember"><small><?php echo _('Remember me'); ?></small></label></p>

<p><?php echo Forms::input('textarea', 'comment_text')?><br />
Erlaubt HTML Tags: a, b, i, u, strike, blockquote, code, br, p, strong, pre</p>

<p><?php echo Forms::input('submit', 'submit', _('Save')); ?></p>

</form>