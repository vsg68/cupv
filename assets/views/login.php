<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Login</title>

		<link rel="stylesheet" href="../css/smoothness/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="../css/style.css" type="text/css" />
		<link rel="stylesheet" href="../css/smoothness/images.css" type="text/css" />
		<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/init.js"></script>

			<?= $css_file; ?>
			<?= $script_file; ?>
    </head>
    <body>
	<div class="main-page">
		<div class='sections <?= ($is_hidden) ? 'hidden' : '' ?>'>
			<span id='logout' title='выход из системы'><img src="/images/xg.png" /></span>

			<?php
				if(isset($pages) ) {
					foreach($pages as $page):
			?>
				<div class='theme ui-widget ui-corner-all box-shadow '>
					<div id='<?= strtolower($page->name) ?>' class='img-pad' title='<?= $page->note ?>'
					<?php
						$filename = $_SERVER['DOCUMENT_ROOT'].'/images/'.strtolower($page->name).'.png';
						//echo $filename;
						if( file_exists($filename))
						 echo " style='background: url(/images/".basename($filename).")'";
					?>
					></div>
					<div class='name'><a href='<?= $page->link ?>'><?= $page->name ?></a></div>
				</div>
			<?php
					endforeach;
				}
			?>
		</div>
		<div class='loginform user-form ui-widget ui-corner-all box-shadow <?= ($is_hidden) ? '' : 'hidden' ?>'>
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: none;">
				<p>
					<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
					<strong>Alert:</strong>
					<span  id='mesg'></span>
				</p>
			</div>
			<form id="usersform" action='#' method='post'>

				<div class='logo'><img src='/gmp.png'></div>
			<table>
				<tr><td class='formlabel'>Имя пользователя:</td><td><input type='text' name='username' value=''></td></tr>
				<tr><td class='formlabel'>Пароль:</td><td><input type='password' name='password' value=''/></td></tr>
			</table>

			</form>
			<div class='submit'><div id='sb'></div></div>
		</div>
	</div>
</body>
</html>
