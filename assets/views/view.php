<div id='log'></div>
<form name='usersform' method='post'>
	<input type='hidden' name='user_id' value='<?= $user->user_id; ?>' >
	<input type='hidden' name='mailbox' value='<?= $user->mailbox; ?>' >
	<fieldset  class="user1">
	<legend><?= $user->mailbox; ?></legend>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' <?php ($user->active == 1) && print ('checked'); ?> >
	   </div>	 
	   <div class='fieldentry'>
			<span class='formlabel'>ФИО:</span>
			<input class='formtext' type='text' name='username' value='<?= $user->username; ?>'  />
	   </div>	
		   
	   <div class='fieldentry'>
			<span class='formlabel'>Пароль:</span>
			<input class='formtext' type='text' name='password' value='<?= $user->password; ?>'   />
	   </div>	
	   <div class='fieldentry'>
			<span class='formlabel'>Путь:</span>
			<input type='text' class='formtext' name='path' value='<?= $user->path; ?>'    />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Сеть:</span>
			<input  class='formtext' type='text' name='allow_nets' value='<?= $user->allow_nets; ?>'  />
	   </div>	
	   <div class='fieldentry'>
			 <span class='formlabel'>Протоколы:</span>
				imap:<input type='checkbox' class='formtext' name='imap' value='2' <?php ($user->imap_enable & 2 ) && print ('checked'); ?>  >&nbsp;&nbsp;
				pop3:<input type='checkbox' class='formtext' name='pop3' value='1' <?php ($user->imap_enable & 1 ) && print ('checked'); ?>  >
		</div>
	</fieldset>
	<fieldset>
		   <legend>Алиасы</legend>	
		<?php foreach ($aliases as $alias): ?>
		   <div class="alias">
			   <input type='text' name='alias[]' value='<?= $alias->alias_name ?>' >
			   <input type='checkbox' name='alias_act[]' value='1' <?php ($alias->active & 1 ) && print ('checked'); ?>>
		   </div>
		<?php endforeach; ?>   
		   <button id='anext'>Еще</button>
	</fieldset>
	<fieldset>
		   <legend>Пересылка</legend>
		<?php foreach ($aliases as $alias): ?>
		   <div class="alias">
			   <input type='text' name='forward[]' value='<?= $alias->delivery_to ?>' />
			   <input type='checkbox' name='forward_act[]' value='1' <?php ($alias->active & 1 ) && print ('checked'); ?>>
		   </div>
		<?php endforeach; ?>      
		   <button id='fnext'>Еще</button>
   </fieldset>
	<div class='submit'><input id='submit_view' type='submit' value='Изменить' onclick=submit_form();></div>
</form>

