<?php foreach($pages as $page): ?>
	<div class='theme' title='<?= $page->note ?>'><p class='name'><a href='<?= $page->link ?>'><?= $page->name ?></a></p></div>
<?php endforeach; ?>
<div class='helper theme'></div>

<a href='/login/logout'>logout<a>
