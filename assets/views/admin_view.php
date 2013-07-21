<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>

	<h4><?= $section->name; ?></h4>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' <?php ($section->active == 1) && print ('checked'); ?> >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Название:</span>
			<input  class='formtext' type='text' name='secton_name' value='<?= $section->name; ?>'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='secton_note' value='<?= $section->note; ?>'  />
	   </div>
		<div class='fieldentry'>
			<span class='formlabel'>Доступ:</span>
			<select class='formtext' name='slevel'>
				<?php foreach ($slevels as $slevel): ?>
				<option value='<?= $slevel->slevel ?>' <?php ($slevel->id == $section->slevel_id ) && print ('selected')?> > <?= $slevel->name ?>
				<?php endforeach; ?>
			</select>
		</div>

		<input type='hidden' name='secton_id' value='<?= $section->id; ?>'  />

<br />
		<h4>Контроллеры(страницы)</h4>
			<table class='atable'>
				<tr><th  class='txt'>Вкладка</th><th  class='txt'>Page</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
		<?php foreach ($controllers as $control): ?>
		   <tr class="alias">
			   <td><input type='text' name='ctrl_name[]' value='<?= $control->name ?>' <?php ($control->active & 1 ) || print ('disabled'); ?> ></td>
			   <td><select name='ctrl_class[]'>
					   <?php foreach( $slevels as $slevel): ?>
						<option value='<?= $slevel->id ?>' <?= ($slevel->id != $control->slevel_id) ? '' : ' selected' ?> ><?= $slevel->name ?></option>
						<?php endforeach; ?>
				    </select>
			    </td>
			   <td><input type='checkbox' name='chk' <?php ($control->active & 1 ) && print ('checked'); ?>></td>
			   <td>
					<input type='hidden' name='ctrl_st[]' value='<?= $control->active ?>'>
				   <input type='hidden' name='ctrl_id[]' value='<?= $control->id ?>'>
				   <span class='delRow  web'>&otimes;</span>
				</td>
		   </tr>
		<?php endforeach; ?>
			</table>

		<div class='submit'><input type='submit' id='submit_secton' value='Изменить'></div>
</form>

