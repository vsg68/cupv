<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>

	<h4><?= $section->name; ?></h4>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' <?php ($section->active == 1) && print ('checked'); ?> >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Название:</span>
			<input  class='formtext' type='text' name='secton_notes' value='<?= $section->name; ?>'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='secton_notes' value='<?= $section->notes; ?>'  />
	   </div>
		<div class='fieldentry'>
			<span class='formlabel'>Доступ:</span>
			<select class='formtext' name='slevel'>
				<?php foreach ($slevels as $slevel): ?>
				<option value='<?= $slevel->slevel ?>' <?php ($slevel->id == $section->slevel_id ) && print ('selected')?> > <?= $slevel->name ?>
				<?php endforeach; ?>
			</select>
		</div>

		<input type='hidden' name='secton_id' value='<?= $secton->id; ?>'  />

<br />
		<h4>Контроллеры(страницы)</h4>
			<table class='atable'>
				<tr><th  class='txt'>контроллер</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
		<?php foreach ($controllers as $control): ?>
		   <tr class="alias">
			   <td><input type='text' name='dom[]' value='<?= $control->name ?>' <?php ($control->active & 1 ) || print ('disabled'); ?> ></td>
			   <td><input type='checkbox' name='chk' <?php ($control->active & 1 ) && print ('checked'); ?>></td>
			   <td>
					<input type='hidden' name='dom_st[]' value='<?= $control->active ?>'>
				   <input type='hidden' name='dom_id[]' value='<?= $control->section_id ?>'>
				   <span class='delRow  web'>&otimes;</span>
				</td>
		   </tr>
		<?php endforeach; ?>
			</table>

		<div class='submit'><input type='submit' id='submit_secton' value='Изменить'></div>
</form>

