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
		<input type='hidden' name='id' value='<?= $id ?>'  />
		<input type='hidden' name='tab' value='<?= $tab ?>' />
		<input type='hidden' name='pid' value='<?= $pid ?>' />
		<h4></h4>
		<?php if($tab == 'rec'): ?>
		   <table>
				<tr>
					<td class='formlabel'>Название:</td>
					<td><input type='text' name='fname' value='<?= isset($data->entry[$id]->fname) ? $data->entry[$id]->fname : '' ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Данные:</td>
					<td><textarea name='fval'><?= isset($data->entry[$id]->fval) ? $data->entry[$id]->fval : '' ?></textarea></td>
				</tr>
			</table>
		<?php elseif($tab == 'cont'): ?>
			<table>
				<tr>
					<td class='formlabel'>Контакт:</td>
					<td><input type='text' name='fval[]' value='<?= isset($data->records[$id]) ? $data->records[$id] : "" ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Должность:</td>
					<td><input type='text' name='fval[]' value='<?= isset($data->records[$id][1]) ? $data->records[$id][1] : "" ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Телефон:</td>
					<td><input type='text' name='fval[]' value='<?= isset($data->records[$id][2]) ? $data->records[$id][2] : "" ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Email:</td>
					<td><input type='text' name='fval[]' value='<?= isset($data->records[$id][3]) ? $data->records[$id][3] : "" ?>'  /></td>
				</tr>
			</table>
		<?php endif; ?>
			<div class='submit'><div id='sb'></div></div>
	</form>
	</div>

<div>

