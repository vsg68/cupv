
<div id='log'><?= $log ?></div>
<form id='usersform' action='' method='post'>

	<h4><?= $section->name ?></h4>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' <?php ($section->active == 1) && print ('checked'); ?> >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Название:</span>
			<input  class='formtext' type='text' name='section_name' value='<?= $section->name; ?>'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='section_note' value='<?= $section->note; ?>'  />
	   </div>
		<div class='fieldentry'>
			<span class='formlabel'>Доступ:</span>
			<select class='formtext' name='slevel_id'>
				<?php foreach ($slevels as $slevel): ?>
				<option value='<?= $slevel->id ?>' <?php ($slevel->id == $section->slevel_id ) && print ('selected')?> > <?= $slevel->name ?>
				<?php endforeach; ?>
			</select>
		</div>

		<input type='hidden' name='section_id' value='<?= $section->id ?>'  />

<br />
		<h4>Контроллеры(страницы)</h4>
			<table class='atable'>
				<tr><th  class='txt'>Имя вкладки</th><th  class='txt'>Page</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
		<?php foreach ($controllers as $control): ?>
		   <tr class="alias">
			   <td>
				   <span class='up'>&#9650;</span>
				   <input type='text' name='ctrl_name[]' value='<?= $control->name ?>' <?php ($control->active & 1 ) || print ('disabled'); ?> ></td>
			   <td><select name='ctrl_class[]'>
						<option value="" class="zero"></option>
					   <?php foreach( $options as $opt): ?>
						<option value='<?= $opt ?>' <?= ($opt != $control->class) ? '' : ' selected' ?> ><?= $opt ?></option>
						<?php endforeach; ?>
				    </select>
			    </td>
			   <td><input type='checkbox' name='chk' <?php ($control->active & 1 ) && print ('checked'); ?>></td>
			   <td>
				   <input type='hidden' name='num[]' value='<?= $control->arrange ?>'>
				   <input type='hidden' name='stat[]' value='<?= $control->active ?>'>
				   <input type='hidden' name='fid[]' value='<?= $control->id ?>'>
				   <span class='delRow  web'>&otimes;</span>
				</td>
		   </tr>
		<?php endforeach; ?>
			</table>

		<div class='submit'><input type='submit' id='submit_ctrl' value='Изменить'></div>
</form>

