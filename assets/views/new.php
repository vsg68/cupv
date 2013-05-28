<div id='log'><?= $log ?></div>
<form name='usersform' action='/users/add' method='post'>
	<fieldset  class="user1">
	<legend>Пользователь</legend>
		   <div class='fieldentry'>
				<span class='formlabel'>ФИО:</span>
				<input class='formtext' type='text' name='fio' />
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
				<input class='formtext' type='text' name='passwd' />
		   </div>	
		   <div class='fieldentry'>
				<span class='formlabel'>Путь:</span>
				<input type='text' class='formtext' name='path' value='/var/tmp' />
		   </div>
		   <div class='fieldentry'>
				<span class='formlabel'>Сеть:</span>
				<input  class='formtext' type='text' name='nets' value='192.168.0.0/24' />
		   </div>	
		   <div class='fieldentry'>
				 <span class='formlabel'>Протоколы:</span>
				 <div class='formtext'>
					imap:<input type='checkbox' name='imap' value='2' checked >&nbsp;&nbsp;
					pop3:<input type='checkbox' name='pop3' value='1' checked>
				</div>
			</div>
	</fieldset>
   <fieldset>
		   <legend>Алиасы</legend>
		   <div class="alias"><input type='text' name='alias[]' value='' ></div>
		   <button id='anext'>Еще</button>
	</fieldset>
	<fieldset>
		   <legend>Пересылка</legend>
		   <div class="alias"><input type='text' name='forward[]' value='' /></div>
		   <button id='fnext'>Еще</button>
   </fieldset>
	<div class='submit'><input type='submit' value='добавить'></div>
</form>
