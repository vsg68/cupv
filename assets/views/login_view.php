
<div id='logout' title='выход из системы'><strong>&times;</strong></div>

<?php foreach($pages as $page): ?>
	<div class='theme'>
		<div class='logomin' title='<?= $page->note ?>'
		<?php
			$filename = $_SERVER['DOCUMENT_ROOT'].'/'.strtolower($page->name).'.png';
			//echo $filename;
			if( file_exists($filename))
			 echo " style='background: url(/".basename($filename).")  0 0 no-repeat; margin-left: 50px;'";
		?>
		></div>
		<div class='name'><a href='<?= $page->link ?>'><?= $page->name ?></a></div>
	</div>
<?php endforeach; ?>
<div class='helper theme'></div>



