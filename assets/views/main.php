<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>
            <script type='text/javascript' language='JavaScript'  src='/jquery-1.8.2.min.js' ></script>
            <script type='text/javascript' language='JavaScript'  src='/jquery.autocomplete.min.js' ></script>
			<script type="text/javascript" src='/init.js'></script>
			<?= $script_file; ?>

			<link rel="stylesheet" href="/main.css" type="text/css" />
			<link rel="stylesheet" href="/main-color.css" type="text/css" />
			<?= $css_file; ?>
			<link rel="stylesheet" href="/autocomp.css" type="text/css" />
    </head>
    <body>
        <div class="container">

			<div class="mainmenu">
				<ul>
				<?php foreach( $menuitems as $item ): ?>
					<li id='<?= $item->class ?>'>
						<a href="/<?= $item->class ?>/"><?= $item->name ?></a>
						<div class='whiteline'><div>
					</li>
				<?php endforeach; ?>
<!--
				<li class='helper'></li>
-->
				</ul>
			</div>

			<div class='border'>
			<?php include($subview.'.php'); ?>
			</div>
        </div>
    </body>
</html>
