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
						<td class='formlabel'>Алиас:</td>
						<td><input class='ui-widget-content ui-corner-all' type='text' name='alias_name' value='<?= isset($data->alias_name) ? $data->alias_name : '' ?>'  /></td>
					</tr>
					<tr>
						<td class='formlabel'>Переадресация:</td>
						<td><input class='ui-widget-content ui-corner-all' type='text' name='delivery_to' value='<?= isset($data->delivery_to) ? $data->delivery_to : '' ?>' /></td>
					</tr>
					<tr>
						<td class='formlabel'>Заметка:</td>
						<td><input class='ui-widget-content ui-corner-all' type='text' name='alias_notes' value='<?= isset($data->alias_notes) ? $data->alias_notes : '' ?>' /></td>
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


