<?php 

use View\Forms;
use Application\Uri;

?>

	<h3 id="comments"><?php echo _('Comments')?></h3> 

	<ol class="commentlist">
<?php $i = 0; ?>
<?php foreach ($comments as $comment): ?>
		<li class="<?php echo ($i % 2 == 0) ? 'alt' : ''; $i++; ?>" id="com<?php echo $comment->getId(); ?>">

			<cite><?php echo $comment->getAuthor(); ?></cite>:
			<br />

			<small class="commentmetadata"><a href="#com<?php echo $comment->getId(); ?>"><?php echo date('d.m.Y H:i', $comment->getDate()); ?></a></small>

			<p><?php echo $comment->getText(); ?></p>

		</li>
<?php endforeach; ?>
	</ol>
	
<p><br /></p>
<h3 id="respond"><?php echo _('New comment'); ?></h3>

<?php echo Forms::form(Uri::to('comment/add/')->param('entry_id', $entry->getId())); ?>
<p><?php echo Forms::input('text', 'comment_author'); ?> <label for="comment_author"><small><?php echo _('Name'); ?></small></label></p>
<p><?php echo Forms::input('text', 'comment_mail'); ?> <label for="comment_mail"><small><?php echo _('E-Mail'); ?></small></label></p>
<p><?php echo Forms::input('text', 'comment_url'); ?> <label for="comment_url"><small><?php echo _('Homepage'); ?></small></label></p>
<p><textarea id="comment_text" name="comment_text" rows="10"></textarea><br />
Erlaubt HTML Tags: a, b, i, u, strike, blockquote, code, br, p, strong, pre</p>

<p><?php echo Forms::input('submit', 'comment_save', _('Save')); ?></p>

</form>