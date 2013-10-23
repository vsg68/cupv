<div >
	<div id='error_title'></div>
<form id='usersform' action='#' method='post'>
	<input type='hidden' name='id' value='<?= (isset($data->id) ? $data->id : '') ?>'  />
	<input type='hidden' name='tab' value='<?= $tab ?>' />
	<h4></h4>
	   <div class='fieldentry'>
			<span class='formlabel'></span>
			<input class='formtext' type='text' name='username' value='<?= isset($data->username) ? $data->username : '' ?>'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'></span>
			<input class='login' type='text' name='login' value='<?= isset($data->mailbox) ? explode('@',$data->mailbox)[0] : '' ?>' />&nbsp;<strong>@</strong>
			<select class='domain' name='domain'>
				<?php foreach( $domains as $domain): ?>
				<option value='<?= $domain->domain_name ?>' <?= ( $domain->domain_name == 'gmpro.ru' ? 'selected': '') ?> ><?= $domain->domain_name ?></option>
				<?php endforeach; ?>
			</select>
	   </div>

	   <div class='fieldentry'>
			<span class='formlabel mkpwd' title=Password'></span>
			<input class='formtext' type='text' name='password' value='<?= isset($data->password) ? $data->password : '' ?>'   />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'></span>
			<input  class='formtext' type='text' name='allow_nets' value='<?= isset($data->allow_nets) ? $data->allow_nets : '192.168.0.0/24' ?>'  />
	   </div>
		<div class='fieldentry'>
			<div class='formlabel'><span class='ptr' id='path'></span></div>
	   </div>
	   <div class='fieldentry'>
			 <span class='formlabel'></span>
			 <input type='checkbox' class='formtext' name='imap' value='1' <?= (isset($data->imap_enable) && $data->imap_enable) ? 'checked' : '' ?> >
		</div>
	   <div class='fieldentry'>
			 <span class='formlabel'></span>
			 <input type='checkbox' class='formtext' name='active' value='1' <?= (isset($data->active) && $data->active) ? 'checked' : '' ?> >
		</div>

	<div class='submit'><input type='button' id='submit' value='Send'></div>
</form>
<div>
<div style='display:none'>
			<img src='/img/x.png' alt='' />
</div>
<script type="text/javascript" language="javascript">
//$('#submit').click( function(e){ trySubmit(); })
</script>
