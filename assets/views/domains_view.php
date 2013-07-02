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
		<?php else: ?>
	   <div class='listbox'>
			<div class='fieldentry'>
				<span class='formlabel'>П/Я рассылки:</span>
				<input class='formtext' type='text' name='all_email' value='<?= preg_replace('/@.+$/','',$domain->all_email); ?>' <?php ($domain->all_enable & 1 ) || print ('disabled'); ?>/>@<?= $domain->domain_name; ?>
			</div>
			<div class='fieldentry'>
				 <span class='formlabel'>Вкл/Выкл:</span>
				<input class='formtext' type='checkbox' name='all_enable' value='1' <?php ($domain->all_enable & 1 ) && print ('checked'); ?> >
			</div>
		</div>
		<?php endif; ?>
		<input type='hidden' name='domain_id' value='<?= $domain->domain_id; ?>'  />
		<input type='hidden' name='domain_name' value='<?= $domain->domain_name; ?>'  />
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
				   <span class='delRow  web'>&otimes;</span>
				</td>
		   </tr>
		<?php endforeach; ?>
			</table>

		<div class='submit'><input type='submit' id='submit_domain' value='Изменить'></div>
</form>

