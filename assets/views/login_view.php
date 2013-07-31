
<div id='logout' title='выход из системы'><strong>&times;</strong></div>

<?php foreach($pages as $page): ?>
	<div class='theme'>
		<div class='logo' title='<?= $page->note ?>' id='<?= strtolower($page->name) ?>'></div>
		<div class='name'><a href='<?= $page->link ?>'><?= $page->name ?></a></div>
	</div>
<?php endforeach; ?>
<div class='helper theme'></div>



