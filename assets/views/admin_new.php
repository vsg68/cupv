
<script type='text/javascript'>
			var ctrl_cell 	= '<select name="ctrl_class[]">' +
				<?php	foreach($options as $opt): ?>
						+ '<option value="<?= $opt ?>"><?= $opt ?></option>'
				<?php endforeach; ?>
						+ '</select>';
</script>

<div id='log'><?= $log ?></div>
<form action='/admin/add' method='post' enctype='multipart/form-data' onsubmit='return validateForm();'>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' checked>
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Раздел:</span>
			<input class='formtext' type='text' name='section_name' value='' placeholder='название'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='section_note' value=''  placeholder='описание' />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Доступ:</span>
			<select  class='formtext' name='slevel_id'>
				<?php foreach( $slevels as $slevel): ?>
				<option value='<?= $slevel->id ?>' <?= ($slevel->slevel) ? '' : ' selected' ?> ><?= $slevel->name ?></option>
				<?php endforeach; ?>
			</select>
	   </div>
	   <div class='fieldentry'>
			 <span class='formlabel'>Логотип:</span>
			 <?= $logo ?>
		</div>

<br />
		<h4>Страницы(контроллеры) раздела</h4>
		<table class='atable'>
			<tr><th  class='txt'>Вкладка</th><th  class='txt'>Page</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
		</table>

	<div class='submit'><input type='submit' id='submit_ctrl' value='Изменить'></div>
</form>

