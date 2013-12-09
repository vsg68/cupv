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
			<input type='hidden' name='id' value='<?= (isset($entries->id) ? $entries->id : '0') ?>'  />
			<input type='hidden' name='tab' value='<?= $tab ?>' />
			<h4></h4>
			   <table>
					<tr>
						<td class='formlabel'>Группа:</td>
						<td><input type='text' name='name' value='<?= isset($entries->name) ? $entries->name : '' ?>'  /></td>
					</tr>
					<tr>
						<td class='formlabel'>Описание:</td>
						<td><input type='text' name='note' value='<?= isset($entries->note) ? $entries->note : '' ?>' /></td>
					</tr>
					<tr>
						<td class='formlabel'>Активно:</td>
						<td><input type='checkbox' class='formtext' name='active' value='1' <?php isset($entries->active) ?  ($entries->active & 1) && print('checked') : print('checked') ?> ></td>
					</tr>
				</table>
				<div class='submit'><div id='sb'></div></div>
		</form>
	<div>
</div>


