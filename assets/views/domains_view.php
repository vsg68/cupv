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

		<div class='submit'><input type='submit' id='submit_domain' value='Изменить'></div>
</form>

