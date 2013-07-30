
<div id='logout' title='выход из системы'><strong>&times;</strong></div>

<?php foreach($pages as $page): ?>
	<div class='theme' title='<?= $page->note ?>' id='<?= strtolower($page->name) ?>'>
		<p class='name'><a href='<?= $page->link ?>'><?= $page->name ?></a></p>
	</div>
<?php endforeach; ?>
<div class='helper theme'></div>



