<div id='log'><?= $log ?></div>
<!--
<form id='usersform' action='#' method='post'>
-->
	<h4>-- Список(верт.) --</h4>
	<?php //foreach( $fields_arr as $field ) ?>
		<table class='records0'>
			<tr class='alias'><th class='txt'>Название</th><th>Тип поля</th><th><div class='add' id='entry' title='Добавить'></div></th></tr>
			<tr>
				<td class='fname'>
					<div class='up'>&#9650;</div>
					<input type='text' name='fname[]' placeholder='название'/></td>
				</td>
				<td class='ftext'>
							<select name='ftype[]'>
								<option value='text' selected>text</option>
								<option value='textarea'>textarea</option>
							</select>
				</td>
				<td><div class="delRow"></div></td>
			</tr>

	   </table>
<h4>Табличные записи</h4>
	   <table class='records1'>

			<tr class='alias'><th class='txt'>Название колонки</th><th><div class='add' id='entry' title='Добавить'></div></th></tr>
			<tr>
				<td class='fname'>
					<div class='up'>&#9650;</div>
					<input type='text' name='fname[]' placeholder='название'/></td>
				</td>
				<td><div class="delRow"></div></td>
			</tr>

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
<!--
</form>
-->

