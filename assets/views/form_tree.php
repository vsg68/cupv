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
			<input type='hidden' name='pid' value='<?= $pid ?>' />
			<h4></h4>
			   <table>
					<tr>
						<td class='formlabel'>Название:</td>
						<td><input type='text' name='name' value='<?= isset($data->name) ? $data->name : '' ?>'  /></td>
					</tr>
					<?php if( !isset($data->id) ): ?>
					<tr>
						<td class='formlabel'>Позиция раздела:</td>
						<td>
							В корне<input type='radio' name='in_root' value='1' checked /> &nbsp;&nbsp;
							В текущем разделе<input type='radio' name='in_root' value='0'  />
						</td>
					</tr>
					<?php endif; ?>
			  </table>
				<div class='submit'><div id='sb'></div></div>
		</form>
	<div>
</div>


