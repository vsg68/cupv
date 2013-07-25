	<div class="editmenu">
		<div id='new'></div>
	</div>
<div id='usrs'>
	<h4>Разделы сайта</h4>
	<div class='aliasesplace'>
		<div>
			<div class='th'>Раздел</div>
			<div class='th'>Описание</div>
		</div>
		<div class='domain_box'>
			<table>
			<?php foreach( $sections as $section ): ?>
			   <tr id="i-<?= $section->id ?>" >
				   <td class="key <?= $section->active == 0 ? 'nonactive':''; ?>"><?= $section->name ?></td>
				   <td class="val"><?= $section->note ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
<br />
	<h4>Используемые страницы</h4>
	<div class='aliasesplace'>
		<div>
			<div class='th'>Контроллер</div>
			<div class='th'>Название раздела</div>
		</div>
		<div class='domain_box'>
			<table>
			<?php foreach( $controllers as $control ): ?>
			   <tr class='noedit' id='n-<?= $control->s_id ?>' >
				   <td class="key" ><?= $control->c_class ?></td>
				   <td class="val"><?= $control->s_name ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
<div id='ed'>
	<?= $sections_block; ?>
</div>
<script type='text/javascript'>
var ctrl_cell 	= '<select name="ctrl_class[]">' +
				  '<option value="" class="zero"></option>' +
	<?php	foreach($options as $opt): ?>
			'<option value="<?= $opt ?>"><?= $opt ?></option>'+
	<?php endforeach; ?>
			'</select>';
</script>
