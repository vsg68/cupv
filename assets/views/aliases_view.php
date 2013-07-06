	<div id='log'><?= $log ?></div>

<form id='usersform' action='#' method='post'>
	<h4><?= $alias_name ?></h4>
	<table class='atable'>
	   <tr><th>mailbox</th><th>on/off</th><th><button class='else'>+</button></th></tr>
		<?php foreach ($aliases as $alias): ?>
			   <tr class="alias">
				   <td><input type='text' name='fwd[]' value='<?= $alias->delivery_to; ?>' <?php ($alias->active & 1 ) || print ('disabled'); ?>></td>
				   <td>
						<input type='checkbox' name='chk' <?php ($alias->active & 1 ) && print ('checked'); ?>>
						<input type='hidden' name='fwd_st[]' value='<?= $alias->active ?>'>
						<input type='hidden' name='fwd_id[]' value='<?= $alias->alias_id ?>'>
					</td>
				   <td><span class='delRow  web'>&otimes;</span></td>
				</tr>

		<?php endforeach; ?>

	</table>
	<input type='hidden' name='alias' value='<?= $alias_name; ?>'>
	<div class='submit'><input type='submit' id='submit_view' value='Изменить'></div>
</form>
<script type="text/javascript">$('.autocomp').autocomplete(options);</script>
