<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>
		<link rel="stylesheet" href="../css/humanity/jquery-ui-1.10.3.custom.min.css" type="text/css" />
		<link rel="stylesheet" href="../css/demo_table_jui.css" type="text/css" />
		<link rel="stylesheet" href="../css/style.css" type="text/css" />


		<script type="text/javascript" language="javascript" src="../js/jquery-1.9.1.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="../js/jquery-ui.min.js"></script>

		<script type="text/javascript" language="javascript" src="../js/init.js"></script>

			<?= $css_file; ?>
			<?= $script_file; ?>
    </head>
    <body>
        <div class="container">
			<div class="content">
				<div class="content-center">
					<?php include($subview.'.php'); ?>
				</div>
			</div>
			<div class="content-left">
				<div id='mail-page' class='theme homepage' title='На главную'></div>
				<?php foreach( $menuitems as $item ): ?>
					<a href="/<?= $item->class ?>/"><div id='<?= $item->class ?>' class='theme' title='<?= $item->name ?>'></div></a>
				<?php endforeach; ?>

			</div>
        </div>
    </body>
</html>
