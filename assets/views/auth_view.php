
<div id='log'><?= $log ?></div>
<form id='usersform' action='' method='post'>

	<h4><?= $auth->login ?></h4>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' <?php ($auth->active == 1) && print ('checked'); ?> >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Login:</span>
			<input  class='formtext' type='text' name='auth_login' value='<?= $auth->login; ?>'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>ФИО:</span>
			<input  class='formtext' type='text' name='auth_note' value='<?= $auth->note; ?>'  />
	   </div>
	  <div class='fieldentry'>
			<div class='formlabel'>Пароль:<span class='web' id='passwd'>&rArr;</span></div>
	   </div>
		<div class='fieldentry'>
			<span class='formlabel'>Профиль:</span>
			<select class='formtext' name='role_id'>
				<?php foreach ($roles as $role): ?>
				<option value='<?= $role->id ?>' <?php ($role->id == $auth->role_id ) && print ('selected')?> > <?= $role->name ?>
				<?php endforeach; ?>
			</select>
		</div>
		<input type='hidden' name='auth_id' value='<?= $auth->id ?>'  />



		<div class='submit'><input type='submit' id='submit_auth' value='Изменить'></div>
</form>

