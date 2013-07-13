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

			<?php if( ! preg_match('/login/',$subview) ): ?>
			<div class="mainmenu">
			<ul>
				<li id='users'><a href="/users/">Пользователи</a><div class='whiteline'><div></li>
				<li id='aliases'><a href="/aliases/">Алиасы</a><div class='whiteline'><div></li>
				<li id='domains'><a href="/domains/">Домены</a><div class='whiteline'><div></li>
				<li id='groups'><a href="/groups/">Группы</a><div class='whiteline'><div></li>
				<li id='logs'><a href="/logs/">Логи</a><div class='whiteline'><div></li>
				<li class='helper'></li>
			</ul>
			</div>
			<?php endif; ?>

			<div class='border'>
			<?php include($subview.'.php'); ?>
			</div>
        </div>
    </body>
</html>
