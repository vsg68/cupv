<div id='log'><?= $log ?></div>
<!--
<form id='usersform' action='#' method='post'>
-->
	<h4>-- Контакты --</h4>
	<?php //foreach( $fields_arr as $field ) ?>
		<div class='fieldentry'>
			<span class='formlabel' title='Название'>Название</span>
			<input class='formtext' type='text' name='' placeholder='название'  />
		</div>
	<?php //endforeach ?>
		<div class='fieldentry'>
			<span class='formlabel' title='Адрес'>Адрес</span>
			<input class='formtext' type='text' name='' placeholder=''  />
		</div>
		<div class='fieldentry'>
			<span class='formlabel' title='Реквизиты'>Реквизиты</span>
			<input class='formtext' type='text' name='' placeholder=''  />
		</div>
		<div class='fieldentry'>
			<span class='formlabel' title='№ договора'>№ договора</span>
			<input class='formtext' type='text' name='' placeholder=''  />
		</div>
		<div class='fieldentry'>
			<span class='formlabel' title='Дата договора'>дата договора</span>
			<textarea name="fname[]" rows=3 placeholder="NS1 name"></textarea>
		</div>


	   <h4>Записи</h4>

		<table class='signs'>
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

