
<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
	<input id='user_id' type='hidden' name='user_id' value='<?= $user->user_id; ?>' >
	<input id='mailbox' type='hidden' name='mailbox' value='<?= $user->mailbox; ?>' >

	<h4><?= $user->mailbox; ?></h4>
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
	   <div class='fieldentry path'>
			<div class='formlabel'>Путь:<span class='web' id='path'><?= ( $user->path )? "&dArr;" : "&rArr;" ?></span></div>
			<?php if( $user->path ): ?>
				<input type='text' class='formtext' name='path' value='<?= $user->path; ?>'/>
			<?php endif; ?>
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Размер п/я:</span>
			<?php
				preg_match('/^([^@]+)@(.+)$/',$user->mailbox, $matches );
				$search_path = isset($user->path) ? $user->path :'/var/vmail/'. $matches[2]. '/'. $matches[1];
				$summ =  exec('/usr/local/bin/sudo -u vmail /usr/bin/du -sh '.$search_path);
				$summ = preg_replace("/^([^\/]+).+$/","$1",$summ);
				echo isset($summ) ? $summ : 'mailbox has no size?';
			?>

	   </div>

		<h4>Алиасы</h4>
			<table class='atable'>
				<tr><th  class='txt'>alias</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
		<?php foreach ($aliases as $alias): ?>
			<?php  if( $alias->alias_name == $user->mailbox) { continue; } ?>
		   <tr class="alias">
			   <td><input class='autocomp' type='text' name='alias[]' value='<?= $alias->alias_name ?>' <?php ($alias->active & 1 ) || print ('disabled'); ?> ></td>
			   <td><input type='checkbox' name='chk' <?php ($alias->active & 1 ) && print ('checked'); ?>></td>
			   <td>
					<input type='hidden' name='alias_st[]' value='<?= $alias->active ?>'>
				   <input type='hidden' name='alias_id[]' value='<?= $alias->alias_id ?>'>
				   <button class='delRow  web'>r</button>
				</td>
		   </tr>
		<?php endforeach; ?>
			</table>

		   <h4>Пересылка</h4>
		   <table class='atable'>
				<tr><th class='txt'>forward</th><th>on/off</th><th><button id='fwd' class='else'>+</button></th></tr>

		<?php foreach ($aliases as $alias): ?>
		<?php  if( $alias->alias_name != $user->mailbox) { continue; } ?>
		   <tr class="alias">
			   <td><input type='text' name='fwd[]' value='<?= $alias->delivery_to ?>'  <?php ($alias->active & 1 ) || print ('disabled'); ?> /></td>
			   <td><input type='checkbox' name='chk' value='' <?php ($alias->active & 1 ) && print ('checked'); ?>></td>
			   <td>
				   <input type='hidden' name='fwd_st[]' value='<?= $alias->active ?>'>
				   <input type='hidden' name='fwd_id[]' value='<?= $alias->alias_id ?>'>
				   <button class='delRow web'>r</button>
				</td>
		   </tr>
		<?php endforeach; ?>
			</table>

	<div class='submit'><input type='submit' id='submit_view' value='Изменить'></div>

</form>
<script type="text/javascript">$('.autocomp').autocomplete(options);</script>
