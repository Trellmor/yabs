<?php
use View\HTML;
use Application\Uri;

header('Content-type: application/atom+xml; charset=UTF-8');

$date = new DateTime();
?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title><?php HTML::out($settings->getSiteTitle()); ?></title>
	<id><?php HTML::out(Uri::to('')); ?></id>
	<link rel="self" href="<?php HTML::out(Uri::to('feed')); ?>" />
	<?php if (count($entries) > 0): ?>
	<updated><?php echo $date->setTimestamp($entries[0]->getDate())->format(DateTime::ATOM); ?></updated>
	<?php endif; ?>
	
	<?php foreach($entries as $entry): ?>
	<entry>
		<id><?php HTML::out(Uri::to('blog/' . $entry->getUri())); ?></id>
		<title><?php HTML::out($entry->getTitle()); ?></title>
		<updated><?php echo $date->setTimestamp($entry->getDate())->format(DateTime::ATOM); ?></updated>
		<author>
			<name><?php HTML::out($entry->getUserName());?></name>
		</author>
		<content type="html"><?php HTML::out($entry->getTeaser() . $entry->getContent()); ?></content>
		<link type="alternate" href="<?php HTML::out(Uri::to('blog/' . $entry->getUri())); ?>" title="<?php HTML::out($entry->getTitle()); ?>" />
	</entry>
	<?php endforeach; ?>
</feed>