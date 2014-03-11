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
		<input type='hidden' name='id' value='<?=  $id ?>'  />
		<input type='hidden' name='tab' value='<?= $tab ?>' />
		<input type='hidden' name='pid' value='<?= $pid ?>' />
		<h4></h4>
		   <table>
				<tr>
					<td class='formlabel'>Значение:</td>
					<td><input type='text' name='name' value='<?= isset($data[0]) ? $data[0] : '' ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Описание:</td>
					<td><input type='text' name='comment' value='<?= isset($data[1]) ? $data[1] : '' ?>'></td>
				</tr>
				<tr>
					<td class='formlabel'>Активно:</td>
					<td><input type='checkbox' class='formtext' name='active' value='1' <?= isset($data[0]) ? (preg_match('/^#/', $data[0]) ? '' : 'checked') : 'checked' ?> ></td>
				</tr>
			</table>
			<div class='submit'><div id='sb'></div></div>

			</div>
	</form>
	</div>

<div>

