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
		   <table>
				<tr>
					<td class='formlabel'>Название:</td>
					<td><input type='text' name='fname' value='<?= isset($data->records[$id]->fname) ? $data->records[$id]->fname : '' ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Данные:</td>
					<td><textarea name='fval'><?= isset($data->records[$id]->fval) ? $data->records[$id]->fval : '' ?></textarea></td>
				</tr>
			</table>
			<div class='submit'><div id='sb'></div></div>
	</form>
	</div>

<div>

