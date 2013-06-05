<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>        
            <script type='text/javascript' language='JavaScript'  src='/jquery-1.4.2.js' ></script>
			<script type="text/javascript" src='/manager.js'></script>
			<script type="text/javascript" src='/init.js'></script>
			<link rel="stylesheet" href="/demo.css" type="text/css" />
    </head>
    <body>
        <div class="container">
			<div class="mainmenu">
			<ul>
				<li id='users'><a href="/users/">Пользователи</a></li>
				<li id='aliases'><a href="/aliases/">Алиасы</a></li>
				<li id='domains'><a href="/domains/">Домены</a></li>
				<li id='groups'><a href="/groups/">Группы</a></li>
				<li class='helper'></li>
			</ul>
			</div>
            <div class="editmenu">
				<div id='new'></div>
            </div>
            <div class='usrs'>
				<div id='ulist'>
					<select id='usrs' size=42>
					<?php foreach($users as $user):?>
						<option value='<?php echo $user->user_id;?>' <?= ( $user->active ) ? '' :'class="disabled"'; ?> ><?php echo $user->mailbox;?></option>
					<?php endforeach;?>
					</select>
				</div>
				<div id='ufields'>
					<div class='view'><h2>Пользователи ООО "ГАЗМЕТАЛЛПРОЕКТ"</h2></div>
				</div>
            </div>
        </div>
    </body>
</html>
