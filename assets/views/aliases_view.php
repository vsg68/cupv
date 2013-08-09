	<div id='log'><?= $log ?></div>

<form id='usersform' action='#' method='post'>
	<h4><?= $aliases[0]->alias_name ?></h4>
	<table class='atable'>
	   <tr><th>mailbox</th><th>on/off</th><th class='else'><div title='Добавить'></div></th></tr>
		<?php foreach ($aliases as $alias): ?>
			   <tr class="alias">
				   <td><input type='text' name='fname[]' value='<?= $alias->delivery_to; ?>' <?php ($alias->active & 1 ) || print ('disabled'); ?>></td>
				   <td>
						<input type='checkbox' name='chk' <?php ($alias->active & 1 ) && print ('checked'); ?>>
						<input type='hidden' name='stat[]' value='<?= $alias->active ?>'>
						<input type='hidden' name='fid[]' value='<?= $alias->alias_id ?>'>
					</td>
				   <td><div class='delRow' title='удалить'></div></td>
				</tr>

		<?php endforeach; ?>

	</table>
	<input type='hidden' name='alias_uid' value='<?= $aliases[0]->uid; ?>'>
	<input type='hidden' name='alias_name' value='<?= $aliases[0]->alias_name; ?>'>
	<div class='submit'><input type='submit' id='submit_view' value='Изменить'></div>
</form>
<script type="text/javascript">$('.autocomp').autocomplete(options);</script>
