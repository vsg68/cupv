
<div id='log'><?= $log ?></div>
<form id='usersform' action='' method='post'>

		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' checked >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Login:</span>
			<input  class='formtext' type='text' name='auth_login' value=''  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>ФИО:</span>
			<input  class='formtext' type='text' name='auth_note' value=''  />
	   </div>
	    <div class='fieldentry'>
			<span class='formlabel mkpwd'>Пароль:</span>
			<input  class='formtext' type='text' name='auth_passwd' value=''  />
	   </div>
		<div class='fieldentry'>
			<span class='formlabel'>Профиль:</span>
			<select class='formtext' name='role_id'>
				<?php foreach ($roles as $role): ?>
				<option value='<?= $role->id ?>' <?php ($role->id == 1 ) && print ('selected')?> > <?= $role->name ?>
				<?php endforeach; ?>
			</select>
		</div>



		<div class='submit'><input type='submit' id='submit_auth' value='Изменить'></div>
</form>

