
<div id='log'><?= $log ?></div>
<form id='recordssform' action='#' method='post'>
	<input id='domain_id' type='hidden' name='domain_id' value='<?= $records->domain_id; ?>' >

	<h4><?= $records->mailbox; ?></h4>
		<div class='fieldentry'>
			<span class='formlabel' title='admin E-MAIL'>Контакт:</span>
			<input class='formtext' type='text' name='contact' placeholder='admin@zone.name'  />
	   </div>
	    <div class='fieldentry'>
			<span class='formlabel' title='время обновления записи'>TTL:</span>
			<input class='formtext' type='text' name='ttl' value='86400' />
	   </div>

		<h4>Записи</h4>
			<table class='atable'>
				<tr><th class='txt'>name</th><th>type</th><th class='txt'>IP</th><th class='else'><div id='alias' title='Добавить'></div></th></tr>
		<?php foreach ($aliases as $alias): ?>
			<?php  if( $alias->alias_name == $records->mailbox) { continue; } ?>
		   <tr class="alias">
			   <td><input class='autocomp' type='text' name='fname[]' value='<?= $alias->alias_name ?>' <?php ($alias->active & 1 ) || print ('disabled'); ?> ></td>
			   <td>
					<input type='hidden' name='stat[]' value='<?= $alias->active ?>'>
					<input type='hidden' name='fid[]' value='<?= $alias->alias_id ?>'>
					<input type='hidden' name='ftype[]' value='0'>
					<input type='checkbox' name='chk' <?php ($alias->active & 1 ) && print ('checked'); ?>>
			   </td>
			   <td><div class='delRow' title='удалить'></div></td>
		   </tr>
		<?php endforeach; ?>
			</table>


	<div class='submit'><input type='submit' id='submit_view' value='Изменить'></div>

</form>
<script type="text/javascript">$('.autocomp').autocomplete({serviceUrl:'/recordss/searchdomain/',type:'post'});</script>
