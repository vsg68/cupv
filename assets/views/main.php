<!DOCTYPE html>
<html>
    <head>
 <!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>        
            <script type='text/javascript' language='JavaScript'  src='jquery-1.4.2.js' ></script>
			<script type="text/javascript" src='manager.js'></script>
			<link rel="stylesheet" href="demo.css" type="text/css" />
    </head>
    <body>
        <div class="container">
            <div class="mainmenu">
				<a href="/users/add" class="btn btn-success">Add a new user</a>
            </div>
            <div class='usrs'>
				<div class='umenu'>
						<div class='ulist'>

							<?php foreach($users as $user):?>
								<div class='usr'><a href="/users/view/<?php echo $user->user_id;?>"> <?php echo $user->mailbox;?></a> </div>
							<?php endforeach;?>

						</div>
				</div>		
				<div class='ufields'></div>
            </div>
        </div>
    </body>
</html>
