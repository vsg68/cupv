<div id='log'><?= $log ?></div>
<!--
<form id='usersform' action='#' method='post'>
-->
	<h4>-- Контакты --</h4>
	<?php //foreach( $fields_arr as $field ) ?>
		<table class='entries'>
			<tr class='line'><td class='ed-0'>Редактировать</td></tr>

			<tr class='fhead'><th class='txt'>Название</th><th>Поле</th><th class='else'><div id='alias' title='Добавить'></div></th></tr>

<tr class='line'><td></td></tr>
			<tr>
				<td class='fname'>Название</td>
				<td class='ftext'><input type='text' name='' placeholder='название'  /></td>
				<td><div class="delRow"></div></td>
			</tr>
<tr class='line'><td></td></tr>
			<tr>
				<td class='fname'>Адрес</td>
				<td class='ftext'><input type='text' name='' placeholder='название'  /></td>
				<td><div class="delRow"></div></td>
			</tr>
<tr class='line'><td></td></tr>
			<tr>
				<td class='fname'>Реквизиты</td>
				<td class='ftext'><textarea name='' rows=5 cols='25'></textarea></td>
				<td><div class="delRow"></div></td>
			</tr>
<tr class='line'><td></td></tr>
			<tr>
				<td class='fname'>№ Договора</td>
				<td class='ftext'><input type='text' name='' placeholder='название'  /></td>
				<td class='ed'><div class="delRow"></div></td>
			</tr>
<tr class='line'><td></td></tr>
			<tr>
				<td class='fname'>Дата договора</td>
				<td class='ftext'><input type='text' name='' placeholder='название'  /></td>
				<td class='ed'><div class="delRow"></div></td>
			</tr>

	   </table>
	   <h4>-- Записи --</h4>

		<table class='records'>
				<tr><th class='txt'>Контакт</th><th>должность</th><th class='txt'>Телефон</th><th class='txt'>Мыло</th><th class='txt'>Заметка</th><th class='else'><div id='alias' title='Добавить'></div></th></tr>
				<tr class="alias">
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><div class="delRow"></div></td>
				</tr>
				<tr class="alias">
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><div class="delRow"></div></td>
				</tr>				<tr class="alias">
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><div class="delRow"></div></td>
				</tr>				<tr class="alias">
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea></td>
					<td><div class="delRow"></div></td>
				</tr>
<!--
				<tr class="alias">
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS1"></td>
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS1"></td>
					<td><div class="delRow"></div></td>
				</tr>
				<tr class="alias">
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS1"></td>
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS1"></td>
					<td><div class="delRow"></div></td>
				</tr>
				<tr class="alias">
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS1"></td>
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS1"></td>
					<td><div class="delRow"></div></td>
				</tr>
-->

		</table>


	<div class='submit'><input type='submit' id='submit_view' value='Добавить' onSubmit='function(){return false;}'></div>
<!--
</form>
-->

