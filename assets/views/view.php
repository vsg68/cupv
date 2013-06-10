
<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
	<input id='user_id' type='hidden' name='user_id' value='<?= $user->user_id; ?>' >
	<input id='mailbox' type='hidden' name='mailbox' value='<?= $user->mailbox; ?>' >
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
			<span class='formlabel'>Сеть:</span>
			<input  class='formtext' type='text' name='allow_nets' value='<?= $user->allow_nets; ?>'  />
	   </div>
	   <div class='fieldentry'>
			 <span class='formlabel'>Протоколы:</span>
				imap:<input type='checkbox' class='formtext' name='imap' value='1' <?php ($user->imap_enable & 1 ) && print ('checked'); ?>  >
		</div>
	   <div class='fieldentry'>
			<span class='formlabel'>Путь:</span><button class='web' id='path'><?= ( $user->path )? '3' : '4' ?></button>
			<?php if( $user->path ): ?>
				<input type='text' class='formtext path' name='path' value='<?= $user->path; ?>'/>
			<?php endif; ?>
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Освоено (Kb):</span>
			<?php
					//$matches = array();
					preg_match('/^([^@]+)@(.+)$/',$user->mailbox, $matches );
					$search_path = isset($user->path) ? $user->path :'/var/vmail/'. $matches[2]. '/'. $matches[1];
					echo file_exists( $search_path) ? exec($user->path) : 'maildir not found...';
			?>

	   </div>
	</fieldset>
	<fieldset>
		<legend>Алиасы</legend>
			<table class='atable'>
				<tr><th>alias</th><th>mailbox</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
		<?php foreach ($aliases as $alias): ?>
			<?php  if( $alias->alias_name == $user->mailbox) { continue; } ?>
		   <tr class="alias">
			   <td><input class='autocomp' type='text' name='alias[]' value='<?= $alias->alias_name ?>' <?php ($alias->active & 1 ) || print ('disabled'); ?> ></td>
			   <td>
				   <input type='hidden' name='alias_st[]' value='<?= $alias->active ?>'>
				   <input type='hidden' name='alias_id[]' value='<?= $alias->alias_id ?>'>
				   <?= $user->mailbox; ?>
			   </td>
			   <td><input type='checkbox' name='chk' <?php ($alias->active & 1 ) && print ('checked'); ?>></td>
			   <td><button class='delRow  web'>r</button></td>
		   </tr>
		<?php endforeach; ?>
			</table>
	</fieldset>
	<fieldset>
		   <legend>Пересылка</legend>
		   <table class='atable'>
				<tr><th>mailbox</th><th>forward</th><th>on/off</th><th><button id='fwd' class='else'>+</button></th></tr>

		<?php foreach ($aliases as $alias): ?>
		<?php  if( $alias->alias_name != $user->mailbox) { continue; } ?>
		   <tr class="alias">
			   <td>
				   <input type='hidden' name='fwd_st[]' value='<?= $alias->active ?>'>
				   <input type='hidden' name='fwd_id[]' value='<?= $alias->alias_id ?>'>
				   <?= $user->mailbox; ?>
				</td>
			   <td><input type='text' name='fwd[]' value='<?= $alias->delivery_to ?>'  <?php ($alias->active & 1 ) || print ('disabled'); ?> /></td>
			   <td><input type='checkbox' name='chk' value='' <?php ($alias->active & 1 ) && print ('checked'); ?>></td>
			   <td><button class='delRow web'>r</button></td>
		   </tr>
		<?php endforeach; ?>
			</table>
   </fieldset>
	<div class='submit'><input type='submit' id='submit_view' value='Изменить'></div>

</form>
<script type="text/javascript">$('.autocomp').autocomplete(options);</script>
