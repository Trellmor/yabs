<?php
use View\Forms;
use Application\Uri;
?>
<h1>Entry</h1>

<?php echo Forms::form(Uri::to('admin/entry/' . (($entry->getId() != -1) ? $entry->getId() : 'new'))); ?>
<div class="form-group">
  <label for="entry_title"><?php echo _('Title'); ?></label>
  <?php echo Forms::input('text', 'entry_title', $entry->getTitle(), ['class' => 'form-control']) ?>
</div>
<ul class="nav nav-tabs">
  <li><a href="#teaser" data-toggle="tab"><?php echo _('Teaser'); ?></a></li>
  <li class="active"><a href="#content" data-toggle="tab"><?php echo _('Content'); ?></a></li>
</ul>
<div class="tab-content">
  <div id="teaser" class="tab-pane form-group">
    <?php echo Forms::input('textarea', 'entry_teaser', $entry->getTeaser(), ['class' => 'form-control tinymce']); ?>
  </div>
  <div id="content" class="tab-pane active form-group">
    <?php echo Forms::input('textarea', 'entry_content', $entry->getContent(), ['class' => 'form-control tinymce']); ?>
  </div>
</div>
<div class="form-group">
  <?php echo Forms::input('submit', 'save', _('Save'), ['class' => 'btn btn-default']); ?>
</div>
<?php Forms::input('submit', 'submit', _('Save')); ?>
</form>
