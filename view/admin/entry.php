<?php
use View\Forms;
use Application\Uri;
?>
<h1 class="page-header">Entry</h1>

<?php echo Forms::form(Uri::to('admin/entry/' . (($entry->getId() != -1) ? $entry->getId() : 'new')), ['role' => 'form']); ?>

<div class="form-group">
  <label for="entry_title"><?php echo _('Title'); ?></label>
  <?php echo Forms::input('text', 'entry_title', $entry->getTitle(), ['class' => 'form-control']) ?>
</div>

<div class="form-group">
  <label for="entry_uri"><?php echo _('URI'); ?></label>
  <?php echo Forms::input('text', 'entry_uri', $entry->getUri(), ['class' => 'form-control']); ?>
</div>

<div class="form-group">
  <label for="entry_date"><?php echo _('Date'); ?></label>
  <div class="input-group datetimepicker">
    <?php echo Forms::input('text', 'entry_date', date('Y-m-d G:H', $entry->getDate()), ['class' => 'form-control', 'data-format' => 'YYYY-MM-DD HH:mm'])?>
    <span class="input-group-addon">
      <span class="glyphicon glyphicon-time"></span>
    </span>
  </div>
</div>

<div class="form-group">
  <label for="category_id"><?php echo _('Category'); ?></label>
  <?php echo Forms::select('category_id', $entry->getCategoryId(), $categories, ['class' => 'form-control']); ?>
</div>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'entry_visible', $entry->getVisible()); ?>
    <?php echo ('Published'); ?>
  </label>
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
</form>
