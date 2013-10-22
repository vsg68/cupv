<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
	<h4></h4>
	   <div class='fieldentry'>
			<span class='formlabel'></span>
			<input class='formtext' type='text' name='username' value=''  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'></span>
			<input class='login' type='text' name='login'/>&nbsp;<strong>@</strong>
			<select class='domain' name='domain'>
				<?php foreach( $domains as $domain): ?>
				<option value='<?= $domain->domain_name ?>' <?= ( $domain->domain_name == 'gmpro.ru' ? 'selected': '') ?> ><?= $domain->domain_name ?></option>
				<?php endforeach; ?>
			</select>
	   </div>

	   <div class='fieldentry'>
			<span class='formlabel mkpwd' title=Password'></span>
			<input class='formtext' type='text' name='password' value=''   />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'></span>
			<input  class='formtext' type='text' name='allow_nets' value='192.168.0.0/24'  />
	   </div>
		<div class='fieldentry'>
			<div class='formlabel'><span class='ptr' id='path'></span></div>
	   </div>
	   <div class='fieldentry'>
			 <span class='formlabel'>imap:</span>
			 <input type='checkbox' class='formtext' name='imap' value='1' checked >
		</div>
	   <div class='fieldentry'>
			 <span class='formlabel'>imap:</span>
			 <input type='checkbox' class='formtext' name='imap' value='1' checked >
		</div>

	<div class='submit'><input type='submit' id='submit_view' value='Send'></div>
</form>
