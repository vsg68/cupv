<div id='log'><?= $log ?></div>

<form id='usersform' action='#' method='post'>
	<input type='hidden' name='id' value='<?= $entries->id ?>' />

	<h4>Список(верт.)</h4>
		<table class='records0'>
			<tr><th width='240px'>Название</th><th width='101px'>Тип поля</th><th><div class='add' id='entry' title='Добавить'></div></th></tr>
			<?php
					if( isset($templ['entry']) ) {
						foreach( $templ['entry'] as $entry ):
			?>
			<tr class='alias'>
				<td class='fname'>
					<input type='text' name='fname[]' value='<?= $entry['fname'] ?>'/>
					<input type='hidden' name='fval[]' value=''/>
				</td>
				<td class='ftext'>
							<select name='ftype[]'>
								<option value='text' <?php $entry['ftype'] == 'text' || print "selected"  ?>>text</option>
								<option value='textarea' <?php $entry['ftype'] != 'textarea' || print "selected" ?>>textarea</option>
							</select>
				</td>
				<td class='else'><div class="delRow"></div></td>
			</tr>
			<?php endforeach; } ?>
	   </table>
	 <br/>
	<h4>Табличные записи</h4>
	   <table class='records1'>
			<tr><th width='240px'>Название колонки</th><th><div class='add' id='records' title='Добавить'></div></th></tr>
			<?php
					if( isset($templ['records']) ) {

						foreach( $templ['records'][0] as $record ):
			?>
			<tr class='alias'>
				<td class='fname'>
					<input type='text' name='tdname[0][]' value='<?= $record ?>'/>
				</td>
				<td><div class="delRow"></div></td>
			</tr>
			<?php endforeach; } ?>
	   </table>


	<div class='submit'><input type='submit' id='submit_view' value='<?= isset($templ['entry']) || isset($templ['records']) ? 'Изменить' : 'Добавить' ?>' ></div>
</form>

