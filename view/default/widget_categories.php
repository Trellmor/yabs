<?php
use View\HTML;
use Application\Uri;
?>
<li>
  <h2><?php echo _('Categories'); ?></h2>
  <ul>
    <?php foreach ($categories as $category): ?>
    <li><a href="<?php HTML::out(Uri::to('category/' . $category->getName())); ?>"><?php HTML::out($category->getName()); ?></a></li>
    <?php endforeach; ?>
  </ul>
</li>