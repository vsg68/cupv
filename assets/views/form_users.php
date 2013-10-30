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
					<td class='formlabel'>Имя пользователя:</td>
					<td><input class='ui-widget-content ui-corner-all' type='text' name='username' value='<?= isset($data->username) ? $data->username : '' ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Пчтовый адрес:</td>
					<td><input class='login' type='text' name='login' value='<?= isset($data->mailbox) ? explode('@',$data->mailbox)[0] : '' ?>' />&nbsp;<strong>@</strong>
						<select class='ui-widget-content ui-corner-all' name='domain'>
							<?php foreach( $domains as $domain): ?>
							<option value='<?= $domain->domain_name ?>' <?= ( $domain->domain_name == 'gmpro.ru' ? 'selected': '') ?> ><?= $domain->domain_name ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='formlabel'>Пароль:<span class='ui-icon ui-icon-gear mkpwd' title='Pass Generator'></span></td>
					<td><input type='text' name='password' value='<?= isset($data->password) ? $data->password : '' ?>'></td>
				</tr>
				<tr>
					<td class='formlabel'>Сети доступа:</td>
					<td><input type='text' name='allow_nets' value='<?= isset($data->allow_nets) ? $data->allow_nets : '192.168.0.0/24' ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Путь к п/я</td>
					<td><input type='text' name='path' value='<?= isset($data->path) ? $data->path : '' ?>'  /></td>
				</tr>
				 <tr>
					<td class='formlabel'>Протокол IMAP:</td>
					<td><input type='checkbox' class='formtext' name='imap' value='1' <?php isset($data->imap_enable) ? ($data->imap_enable & 1) && print('checked') : print('checked') ?> ></td>
				</tr>
				<tr>
					<td class='formlabel'>Активно:</td>
					<td><input type='checkbox' class='formtext' name='active' value='1' <?php isset($data->active) ?  ($data->active & 1) && print('checked') : print('checked') ?> ></td>
				</tr>
			</table>
			<div class='submit'><div id='sb'></div></div>
<!--
				<button aria-disabled="false" role="button" class="ui-button ui-widget ui-corner-all ui-state-default ui-button-text-only" id="submit">
					<span class="ui-button-text">Send</span>
				</button>
-->
			</div>
	</form>
	</div>

<div>

