<div class='box-shadow'>

	<div class='user-form ui-widget ui-corner-all box-shadow'>
		<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: none;">
			<p>
				<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				<strong>Alert:</strong>
				<span  id='mesg'></span>
			</p>
		</div>
		<form id='usersform' action='#' method='post'>
			<input type='hidden' name='id' value='<?= (isset($data->id) ? $data->id : '0') ?>'  />
			<input type='hidden' name='tab' value='<?= $tab ?>' />
			<h4></h4>
			   <table>
					<tr>
						<td class='formlabel noname'>Задача:</td>
						<td><input type='text' name='act' value='<?= isset($data->act) ? $data->act : '' ?>' placeholder='Название задачи' /></td>
					</tr>
					<tr>
						<td class='formlabel noname'>Выполнено:</td>
						<td><input type='checkbox' class='formtext' name='done' value='1' <?php isset($data->done) ?  ($data->done & 1) && print('checked') : '' ?> ></td>
					</tr>
					<tr>
						<td class='formlabel noname'>Следующий запуск:</td>
						<td><input class='date_field'  type='text' name='nextlaunch' value='<?= isset($data->nextlaunch) ? $data->nextlaunch : '' ?>' /></td>
					</tr>
					<tr>
						<td class='formlabel'>Период:</td>
						<td><input class='spin_field' type='text' name='period' value='<?= isset($data->period) ? $data->period : '30' ?>' />&nbsp;дней</td>
					</tr>
					<tr>
						<td class='formlabel'>Alarm Before:</td>
						<td><input class='spin_field' type='text' name='alarmbefore' value='<?= isset($data->alarmbefore) ? $data->alarmbefore : '7' ?>' />&nbsp;дней</td>
					</tr>
					<tr>
						<td class='formlabel'>Email:</td>
						<td><input type='text' name='email' value='<?= isset($data->email) ? $data->email : '' ?>' placeholder='email ответственного за задачу' /></td>
					</tr>
					<tr>
						<td class='formlabel'>Сообщение:</td>
						<td><textarea name='message' placeholder='Текст сообщения'><?= isset($data->message) ? $data->message : '' ?></textarea></td>
					</tr>
					<tr>
						<td class='formlabel'>Активно:</td>
						<td><input type='checkbox' class='formtext' name='active' value='1' <?php isset($data->active) ?  ($data->active & 1) && print('checked') : print('checked') ?> ></td>
					</tr>

				</table>
				<div class='submit'><div id='sb'></div></div>
		</form>
	<div>
</div>


