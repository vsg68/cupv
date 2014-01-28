<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>

		<link rel="stylesheet" href="../css/smoothness/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="../css/demo_table_jui.css" type="text/css" />
		<link rel="stylesheet" href="../css/TableTools_JUI.css" type="text/css" />
		<link rel="stylesheet" href="../css/style.css" type="text/css" />
		<link rel="stylesheet" href="../css/smoothness/images.css" type="text/css" />

		<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/TableTools.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/TableTools.plugins.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.simplemodal.js"></script>
		<script type="text/javascript" language="javascript" src="../js/init.js"></script>

			<?= $css_file; ?>
			<?= $script_file; ?>
    </head>
    <body>
        <div class="container">
			<table class='container-inner'>
				<tr>
					<td class="content-top" colspan=2>

					<?php
						if(isset($pages) ) {
							foreach($pages as $page):
					?>

					<div class='ui-widget ui-corner-all pagemenu'>
						<a href='<?= $page->link ?>'>
							<div id='menu_<?= strtolower($page->link) ?>'class='pagetabmenu <?= ($menuitems[0]->section_id == $page->id) ? 'pagetabmenu-selected ' : '' ?>' title='<?= $page->name ?>'
							<?php
								$filename = $_SERVER['DOCUMENT_ROOT'].'/images/'.strtolower($page->name).'_small.png';
								//echo $filename;
								if( file_exists($filename))
								 echo " style='background: url(/images/".basename($filename).")'";
							?>>
							</div>
						</a>
					</div>

					<?php
							endforeach;
						}
					?>
		</div>
					</td>
				</tr>
				<tr>
					<td class="content-left">
						<div class='ui-widget ui-corner-all pagemenu logout'>
							<a href='/login/logout'><div id='_exit' class='pagetabmenu ' title='Logout' style='background: url(/images/exit.png)'></div></a>
						</div>

					<?php foreach( $menuitems as $item ): ?>
						<div class='theme box-shadow ui-corner-all' title='<?= $item->name ?>'>
							<a href="/<?= $item->class ?>/"><div  id='<?= $item->class ?>'  class='pagetab'></div></a>
						</div>
					<?php endforeach; ?>
					</td>
					<td class="content">
						<?php include($subview.'.php'); ?>
					</td>
				</tr>
			</table>
		</div>
    </body>
</html>
