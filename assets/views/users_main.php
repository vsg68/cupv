<div class="editmenu">
	<div id='domains_flt'>Домен:
		<select>
			<option value='' selected></option>
			<?php foreach($domains as $domain):?>
				<option value='<?php echo $domain->domain_name;?>' ><?php echo $domain->domain_name; ?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div class='lb_filter'> ФИО (filter):
		<input type='text' id='fltr'>
	</div>
	<div id='new'></div>
</div>
<div id='usrs'>
	<div class='aliasesplace'>
		<div>
			<div class='th'>mailbox</div>
			<div class='th'>name</div>
		</div>
		<div class='aliases_box'>
			<table>
			<?php foreach( $users as $user ): ?>
			   <tr sid="<?= $user->user_id ?>" cname="<?= $user->mailbox ?>">
				   <td class="key <?= $user->active == 0 ? 'nonactive':''; ?>"><?= $user->mailbox ?></td>
				   <td class="val"><?= $user->username ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
<div id='ed'>
	<?= $users_block ?>
</div>

