<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>

	<h4><?= $domain->domain_name; ?></h4>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' <?php ($domain->active == 1) && print ('checked'); ?> >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='domain_notes' value='<?= $domain->domain_notes; ?>'  />
	   </div>
	   <?php if( $domain->delivery_to != 'virtual' ): ?>
	   <div class='fieldentry'>
			 <span class='formlabel'>Транспорт:</span>
				<input class='formtext' type='text' name='delivery_to' value='<?= $domain->delivery_to; ?>'  />
		</div>
		<?php endif; ?>
		<input type='hidden' name='domain_id' value='<?= $domain->domain_id; ?>'  />
			<input type='hidden' name='dom_alias' value='<?= $domain->domain_name; ?>'  />
<br />
		<h4>Алиасы домена</h4>
			<table class='atable'>
				<tr><th  class='txt'>alias</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
		<?php foreach ($aliases as $alias): ?>
		   <tr class="alias">
			   <td><input type='text' name='dom[]' value='<?= $alias->domain_name ?>' <?php ($alias->active & 1 ) || print ('disabled'); ?> ></td>
			   <td><input type='checkbox' name='chk' <?php ($alias->active & 1 ) && print ('checked'); ?>></td>
			   <td>
					<input type='hidden' name='dom_st[]' value='<?= $alias->active ?>'>
				   <input type='hidden' name='dom_id[]' value='<?= $alias->domain_id ?>'>
				   <button class='delRow  web'>r</button>
				</td>
		   </tr>
		<?php endforeach; ?>
			</table>

		<div class='submit'><input type='submit' id='submit_domain' value='Изменить'></div>
</form>

