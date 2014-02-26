<?php
use Application\Uri;
use View\Forms;
?>
<h1 class="page-header"><?php echo _('Settings'); ?></h1>

<?php echo Forms::form(Uri::to('admin/settings'), ['role' => 'form']); ?>

<div class="form-group">
  <label for="site_title"><?php echo _('Site title'); ?></label>
  <?php echo Forms::input('text', 'site_title', $settings->getSiteTitle(), ['class' => 'form-control']) ?>
</div>

<div class="form-group">
  <label for="entries_per_page"><?php echo _('Entries per page'); ?></label>
  <?php echo Forms::input('text', 'entries_per_page', $settings->getEntriesPerPage(), ['class' => 'form-control']) ?>
</div>

<div class="form-group">
  <label for="datetime_format"><?php echo _('Date and time format'); ?></label>
  <?php echo Forms::input('text', 'datetime_format', $settings->getDateTimeFormat(), ['class' => 'form-control']) ?>
</div>


<h2 class="sub-header"><?php echo _('Akismet'); ?></h2>

<div class="checkbox">
  <label>
    <?php echo Forms::input('checkbox', 'akismet', $settings->getAkismet()); ?>
    <?php echo ('Akismet'); ?>
  </label>
</div>
<div class="form-group">
  <label for="akismet_key"><?php echo _('Akismet key'); ?></label>
  <?php echo Forms::input('text', 'akismet_key', $settings->getAkismetKey(), ['class' => 'form-control']) ?>
</div>

<div class="form-group">
  <?php echo Forms::input('submit', 'save', _('Save'), ['class' => 'btn btn-default']); ?>
</div>

</form>