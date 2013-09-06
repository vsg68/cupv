
<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
<?php foreach ($records as $record): ?>
	<?php  if( $record->type == 'SOA'): ?>
		<input type='hidden' name='domain_id' value='<?= $record->domain_id ?>' >
		<input type='hidden' name='fname[]' value='<?= $record->name ?>' >
		<input type='hidden' name='fid[]' value='<?= $record->id ?>' >
		<input type='hidden' name='stat[]' value='1' >
		<input type='hidden' name='faddr[]' value='' >
		<input type='hidden' name='ftype[]' value='SOA' >

		<h4><?= $record->name ?></h4>
		<div class='fieldentry'>
			<span class='formlabel' title='admin E-MAIL'>Контакт:</span>
			<input class='formtext' type='text' name='content' value='<?php $c = explode(' ',$record->content); echo preg_replace('/\./','@',$c[1],1); ?>'  />
	   </div>
	    <div class='fieldentry'>
			<span class='formlabel' title='время обновления записи'>TTL:</span>
			<input class='formtext' type='text' name='ttl' value='<?= $record->ttl ?>' />
	   </div>
	   <h4>Записи</h4>
	   <table class='atable'>
		   <tr><th class='txt'>hostname</th><th>type</th><th class='txt'>name/IP</th><th class='else'><div id='alias' title='Добавить'></div></th></tr>
	<?php continue; endif; ?>

	   <tr class="alias">
		   <td><input class='autocomp' type='text' name='fname[]' value='<?= $record->name ?>'></td>
		   <td>
				<select name='ftype[]'>
				<?php foreach( array('NS','A','MX','CNAME') as $type ): ?>
							<option value='<?= $type ?>' <?php ($type == $record->type) && print('selected') ?>><?= $type ?></option>
				<?php endforeach; ?>
				</select>
		   </td>
		   <td><input type='text' name='faddr[]' value='<?= $record->content ?>'></td>
		   <td>
				<input type='hidden' name='fid[]' value='<?= $record->id ?>'>
				<input type='hidden' name='stat[]' value='1'>
			   <div class='delRow' title='удалить'></div>
		   </td>
	   </tr>

	<?php endforeach; ?>
		</table>


	<div class='submit'><input type='submit' id='submit_view' value='Изменить'></div>

</form>

