<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
	<h4>-- имя шаблона --</h4>
	<?php foreach( $fields_arr as $field ) ?>
		<div class='fieldentry'>
			<span class='formlabel' title='admin E-MAIL'><?= $field->sign ?></span>
			<input class='formtext' type='text' name='<?= $field->name ?>' placeholder='<?= $field->placeholder ?>'  />
		</div>
	<?php endforeach ?>

	   </div>
	   <h4>Записи</h4>

		<table class='atable'>
				<tr><th class='txt'>name</th><th>type</th><th class='txt'>IP</th><th class='else'><div id='alias' title='Добавить'></div></th></tr>
				<tr class="alias">
					<td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td>
						<select name="ftype[]">
							<option value="NS">NS</option>
							<option value="A" selected>A</option>
							<option value="MX">MX</option>
							<option value="CNAME">CNAME</option>
						</select>
					</td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS1"></td>
					<td><div class="delRow"></div></td>
				</tr>
				<tr class="alias">
					<td><input type="text" name="fname[]" placeholder="NS2 name"></td>
					<td>
						<select name="ftype[]">
							<option value="NS">NS</option>
							<option value="A" selected>A</option>
							<option value="MX">MX</option>
							<option value="CNAME">CNAME</option>
						</select>
					</td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS2"></td>
					<td><div class="delRow"></div></td>
				</tr>

		</table>


	<div class='submit'><input type='submit' id='submit_view' value='Добавить'></div>
</form>

