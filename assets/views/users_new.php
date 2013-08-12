<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
	<input type='hidden' name='active' value='1'>
	<h4>Ввод нового пользователя.</h4>
	   <div class='fieldentry'>
			<span class='formlabel'>ФИО:</span>
			<input class='formtext' type='text' name='username' value=''  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Логин:</span>
			<input class='login' type='text' name='login'/>&nbsp;<strong>@</strong>
			<select class='domain' name='domain'>
				<?php foreach( $domains as $domain): ?>
				<option value='<?= $domain->domain_name ?>' <?= ( $domain->domain_name == 'gmpro.ru' ? 'selected': '') ?> ><?= $domain->domain_name ?></option>
				<?php endforeach; ?>
			</select>
	   </div>

	   <div class='fieldentry'>
			<span class='formlabel mkpwd' title='создать пароль'>Пароль:</span>
			<input class='formtext' type='text' name='password' value=''   />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Сеть:</span>
			<input  class='formtext' type='text' name='allow_nets' value='192.168.0.0/24'  />
	   </div>
	   <div class='fieldentry'>
			 <span class='formlabel'>Протоколы:</span>
				imap:<input type='checkbox' class='formtext' name='imap' value='1' checked >
		</div>
		<div class='fieldentry'>
			<div class='formlabel'>Путь:<span class='ptr' id='path'></span></div>

	   </div>

		   <h4>Алиасы</h4>
			<table class='atable'>
				<tr><th class='txt'>alias</th><th>on/off</th><th class='else'><div id='alias' title='Добавить'></div></th></tr>
			</table>
		 <h4>Пересылка</h4>
		 <table class='atable'>
			<tr><th class='txt'>mailbox</th><th>on/off</th><th class='else'><div id='fwd' title='Добавить'></div></th></tr>
		</table>

	<div class='submit'><input type='submit' id='submit_view' value='Добавить'></div>
</form>

