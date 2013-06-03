<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>        
            <script type='text/javascript' language='JavaScript'  src='/jquery-1.4.2.js' ></script>
			<script type="text/javascript" src='/manager.js'></script>
			<link rel="stylesheet" href="/demo.css" type="text/css" />
    </head>
    <body>
        <div class="container">
            <div class="mainmenu">
				<a id='newusr' href="/users/new" >Add a new user</a>
            </div>
            <div class='usrs'>
<!--
				<div class='umenu'>
-->
						<div id='ulist'>
							<select id='usrs' size=42>
							<?php foreach($users as $user):?>
								<option value='<?php echo $user->user_id;?>' <?= ( $user->active ) ? '' :'class="disabled"'; ?> ><?php echo $user->mailbox;?></option>
							<?php endforeach;?>
							</select>
						</div>
<!--
				</div>		
-->
				<div id='ufields'>
					<div class='view'><h2>Пользователи ООО "ГАЗМЕТАЛЛПРОЕКТ"</h2></div>
				</div>
            </div>
        </div>
    </body>
</html>
