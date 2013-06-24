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
			<?= $css_file; ?>
			<link rel="stylesheet" href="/autocomp.css" type="text/css" />
    </head>
    <body>
        <div class="container">
			<div class="mainmenu">
			<ul>
				<li id='users'><strong class='web label'>m</strong><a href="/users/">Пользователи</a></li>
				<li id='aliases'><strong class='web label'>'</strong><a href="/aliases/">Алиасы</a></li>
				<li id='domains'><strong class='web label'>ь</strong><a href="/domains/">Домены</a></li>
				<li id='groups'><strong class='web label'>й</strong><a href="/groups/">Группы</a></li>
				<li class='helper'></li>
			</ul>
			</div>
			<?php include($subview.'.php'); ?>
        </div>
    </body>
</html>
