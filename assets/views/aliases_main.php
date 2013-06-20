<div class="editmenu">
	<div id='domains_flt'>Домен:
		<select>
			<option value='' selected></option>
			<?php foreach($domains as $domain):?>
				<option value='<?php echo $domain->domain_name;?>' ><?php echo $domain->domain_name; ?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div class='lb_filter'> Mail (filter):
		<input type='text' id='fltr'>
	</div>
	<div id='new'></div>
</div>
<div class='usrs'>
	<div id='aliasesplace'>
		<div>
			<div class='th'>alias</div>
			<div class='th'>mailbox</div>
		</div>
		<div id='aliases_box'>
			<table>
			<?php foreach( $aliases_arr as $alias => $delivered ): ?>
			   <tr>
				   <td class="key"><?=  $alias ?></td>
				   <td class="val"><?php  sort($delivered); echo implode(',<br>', $delivered); ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
	<div id='ed'>
		<?= $aliases_block; ?>
	</div>
</div>
