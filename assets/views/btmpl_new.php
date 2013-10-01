<div id='log'><?= $log ?></div>

<form id='usersform' action='#' method='post'>
	<input type='hidden' name='id' value='<?= $entries->id ?>' />

	<h4>-- Список(верт.) --</h4>
		<table class='records0'>
			<tr class='alias'><th>Название</th><th>Тип поля</th><th><div class='add' id='entry' title='Добавить'></div></th></tr>
			<?php
					if( isArray($entries->templ['entry']) ) {
						foreach( $entries->templ['entry'] as $entry ):
			?>
			<tr>
				<td class='fname'>
					<?php if( $entry['ftype'] == 'text'): ?>
					<input type='text' name='fname[]' value='<?= $entry['fname'] ?>'/>
					<?php else: ?>
					<textarea name='fname[]' rows='3'><?= $entry['fname'] ?></textarea>
					<?php endif; ?>
				</td>
				<td class='ftext'>
							<select name='ftype[]'>
								<option value='text' selected>text</option>
								<option value='textarea'>textarea</option>
							</select>
				</td>
				<td><div class="delRow"></div></td>
			</tr>
			<?php endforeach; } ?>
	   </table>
<h4>Табличные записи</h4>
	   <table class='records1'>
			<tr class='alias'><th>Название колонки</th><th><div class='add' id='records' title='Добавить'></div></th></tr>
			<?php
					if( isArray($entries->templ['records']) ) {
						foreach( $entries->templ['records'] as $record ):

			?>
			<tr>
				<td class='fname'><input type='text' name='tdname[]' value='<?= $record ?>'/></td>
				<td><div class="delRow"></div></td>
			</tr>
			<?php endforeach; } ?>
	   </table>
<!--
	   <div class='lb'>Записи (таблица)</div>
	   <div class='tab-init'>
			<label>cols:</label>
			 <select name='cols'>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
				</select>
			<label>rows:</label>
			 <select name='rows'>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
				</select>
			<div class='add' id='records'></div>
		</div>

		<div id='tab'></div>
-->

	<div class='submit'><input type='submit' id='submit_view' value='Добавить' onSubmit='function(){return false;}'></div>
</form>

