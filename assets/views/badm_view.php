<div id='log'><?= $log ?></div>
<!--
<form id='usersform' action='#' method='post'>
-->
	<div class='ed-0' title='Редактировать'></div>
	<?php if( isset($templ['entry']) ): ?>
		<h4>Контакты</h4>
		<table class='entries'>

			<tr class='fhead hidden'><th>Название</th><th>Поле</th><th class='else'><div id='alias' title='Добавить'></div></th></tr>
		<?php foreach ($templ['entry'] as $entry): ?>
			<tr class='line'><td></td></tr>
			<tr>
				<td class='fname'>
					<input type='hidden' name='fname[]' value='<?= $entry['fname'] ?>'/>
					<?= $entry['fname'] ?>
				</td>
				<td class='ftext'>
				<?php if($entry['ftype'] == 'text'): ?>
					<input type='text' name='fval[]' value='<?= $entry['fval'] ?>'/>
				<?php else: ?>
					<textarea name='fval[]'>'<?= $entry['fval'] ?></textarea>
				<?php endif; ?>
					<input type='hidden' name='ftype[]' value='<?= $entry['ftype'] ?>'/>
				</td>
				<td class='else'><div class="delRow  hidden"></div></td>
			</tr>
		<?php endforeach; ?>
<!--
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
-->

	   </table>

	<?php endif; ?>

<br />
	   <h4>Записи</h4>

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

