<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
	<input type='hidden' name='id' value='<?= $entries->id ?>' />
	<div class='ed-0' title='Редактировать'></div>
	<?php if( isset($templ['entry']) ): ?>
		<h4>Контакты</h4>
		<table class='entries'>

			<tr class='fhead hidden'><th>Название</th><th>Поле</th><th class='else'><div class='add' id='entry' title='Добавить'></div></th></tr>
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
	   </table>

	<?php endif; ?>

<br />

	<?php if( isset($templ['records']) ): ?>
		<h4>Записи</h4>
		<table class='records'>

			<?php foreach ($templ['records'] as $key=>$record): ?>
			<tr>
				<?php
					foreach ($record as $field) {

						if ($key == 0)
							echo "<td class='t-h'>". $field ."<input type='hidden' name='tdname[". $key ."][]' value='".$field."'></td>";
						else
							echo "<td class='tdarea'><textarea name='tdname[". $key ."][]'>" . $field ."</textarea></td>";
					}

					if ($key == 0)
						echo "<td class='else t-h noborder'><div id='record' class='add hidden' title='Добавить'></div></td>";
					else
						echo "<td class='noborder'><div class='delRow  hidden'></div></td>"
				?>
			</tr>
		<?php endforeach; ?>

		</table>
	<?php endif; ?>

	<div class='submit'><input type='submit' id='submit_view' value='Добавить' onSubmit='function(){return false;}'></div>
</form>

