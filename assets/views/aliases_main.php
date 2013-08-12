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
		<div id='home' title='главная'></div>
		<div id='new' title='новая запись'></div>
</div>
<div id='usrs'>
	<div class='aliasesplace'>
		<div>
			<div class='th'>alias</div>
			<div class='th'>mailbox</div>
		</div>
		<div class='aliases_box'>
			<table>
			<?php
			$sect='';
			$is_change = 1;

			foreach($aliases as $alias) {

				if($sect != $alias->alias_name) {

					$sect = $alias->alias_name;

					if( $is_change = ($is_change) ? 0 : 1 )
						echo "</td></tr>";
			?>
			   <tr sid="<?= $alias->alias_id ?>" sname="<?= $alias->alias_name ?>">
				   <td class="key"><?=  $alias->alias_name ?></td>
				   <td class="val">

			<?php
				}  // endif

				echo "<div class='". ($alias->active == 0 ? 'nonactive' : '') ."'>".$alias->delivery_to."</div>";

			}   // endforeach
			 ?>
			</table>
		</div>
	</div>
</div>
<div id='ed'>
	<?= $aliases_block; ?>
</div>

