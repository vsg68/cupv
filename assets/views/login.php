<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>

<!--
		<link rel="stylesheet" href="../css/demo_table_jui.css" type="text/css" />
-->
		<link rel="stylesheet" href="../css/smoothness/jquery-ui.min.css" type="text/css" />
<!--
		<link rel="stylesheet" href="../css/TableTools_JUI.css" type="text/css" />
-->
		<link rel="stylesheet" href="../css/style.css" type="text/css" />
		<link rel="stylesheet" href="../css/smoothness/images.css" type="text/css" />


		<script type="text/javascript" language="javascript" src="../js/jquery-1.9.1.js"></script>
<!--
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
-->
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<!--
		<script type="text/javascript" language="javascript" src="../js/TableTools.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.simplemodal.js"></script>
-->
		<script type="text/javascript" language="javascript" src="../js/init.js"></script>

			<?= $css_file; ?>
			<?= $script_file; ?>
    </head>
    <body>

<div class='sections <?= ($is_hidden) ? 'hidden' : '' ?>'>
	<div id='logout' title='выход из системы'><strong>&times;</strong></div>


	<?php
		if(isset($pages) ) {
			foreach($pages as $page):
	?>
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
	<?php
			endforeach;
		}
	?>
	</div>
</div>
<div class='login box-shadow <?= ($is_hidden) ? '' : 'hidden' ?>'>
	<div class='user-form ui-widget ui-corner-all box-shadow'>
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: none;">
		<p>
			<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
			<strong>Alert:</strong>
			<span  id='mesg'></span>
		</p>
	</div>
	<form action='#' method='post'>
		<div class='logo'><img src='/gmp.png'></div>
		<p><input type=text name='username' value='' /></p>
		<p><input type=password name='passwd' value='' /><p>

<!--
		<div class='submit'>
			<button aria-disabled="false" role="button" class="ui-button ui-widget ui-corner-all ui-state-default ui-button-text-only" id="submit">
				<span class="ui-button-text">Вход</span>
			</button>
		</div>
-->
	</form>
	<div class='submit'><div id='sb'></div></div>
</div>
</body>
</html>
