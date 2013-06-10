<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
	<fieldset  class="user1">
	<legend>Пользователь</legend>
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
			<span class='formlabel'>Пароль:</span>
			<input class='formtext' type='text' name='password' value=''   />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Сеть:</span>
			<input  class='formtext' type='text' name='allow_nets' value='192.168.0.0/24'  />
	   </div>
	   <div class='fieldentry'>
			 <span class='formlabel'>Протоколы:</span>
				imap:<input type='checkbox' class='formtext' name='imap' value='2' checked >
		</div>
		<div class='fieldentry'>
			<span class='formlabel'>Путь:</span>
			<button id='path' >+</button>

	   </div>
	</fieldset>
   <fieldset>
		   <legend>Алиасы</legend>
			<table class='atable'>
				<tr><th>alias</th><th>mailbox</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
			</table>
	</fieldset>
	<fieldset>
		 <legend>Пересылка</legend>
		 <table class='atable'>
			<tr><th>mailbox</th><th>forward</th><th>on/off</th><th><button id='fwd' class='else'>+</button></th></tr>
		</table>
   </fieldset>
	<div class='submit'><input type='submit' id='submit_view' value='Добавить'></div>
</form>

