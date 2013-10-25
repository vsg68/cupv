<div >
	<div class='ui-state-error'></div>
<form id='usersform' action='#' method='post'>
	<input type='hidden' name='id' value='<?= (isset($data->id) ? $data->id : '0') ?>'  />
	<input type='hidden' name='tab' value='<?= $tab ?>' />
	<h4></h4>
	   <div class='fieldentry'>
			<span class='formlabel'></span>
			<input class='formtext' type='text' name='alias_name' value='<?= isset($data->alias_name) ? $data->alias_name : '' ?>'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'></span>
			<input class='login' type='text' name='delivery_to' value='<?= isset($data->delivery_to) ? $data->delivery_to : '' ?>' />&nbsp;<strong>@</strong>

	   </div>

	   <div class='fieldentry'>
			<span class='formlabel mkpwd' title=Password'></span>
			<input class='formtext' type='text' name='alias_notes' value='<?= isset($data->alias_notes) ? $data->alias_notes : '' ?>'   />
	   </div>

	   <div class='fieldentry'>
			 <span class='formlabel'></span>
			 <input type='checkbox' class='formtext' name='active' value='1' <?php isset($data->active) ?  ($data->active & 1) && print('checked') : print('checked') ?> >
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

