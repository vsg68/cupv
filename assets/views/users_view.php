<div class="editmenu">
	<div id='domains_flt'>Домен:
		<select>
			<option value='' selected></option>
			<?php foreach($domains as $domain):?>
				<option value='<?php echo $domain->domain_name;?>' ><?php echo $domain->domain_name; ?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div id='lb'>ООО "ГАЗМЕТАЛЛПРОЕКТ"</div>
	<div id='new'></div>
</div>
<div class='usrs'>
	<div id='ulist'>
		<select id='usrs' size=42>
		<?php foreach($users as $user):?>
			<option value='<?php echo $user->user_id;?>' <?= ( $user->active ) ? '' :'class="disabled"'; ?> ><?php echo $user->mailbox;?></option>
		<?php endforeach;?>
		</select>
	</div>
	<div id='ufields'>
		<div class='view'><h2><img src='/gmp.png' /></h2></div>
	</div>
</div>
