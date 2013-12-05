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
			   	<?php if( isset($data->domain_id) ): ?>
					<input type='hidden' name='domain_id' value='<?= $data->domain_id ?>'  />
					<tr>
						<td class='formlabel'>Hostname:</td>
						<td><input type='text' name='name' value='<?= isset($data->name) ? $data->name : '' ?>'  /></td>
					</tr>
					<tr>
						<td class='formlabel'>Тип:</td>
						<td>
							<select name='type'>
								<?php foreach( array('SOA','NS','MX','A','TXT') as $entry ): ?>
								<option value='<?= $entry ?>' <?= ($entry == ( isset($data->type) ? $data->type : '') ? ' selected' : '') ?>> <?= $entry ?> </option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td class='formlabel'>name/ip:</td>
						<td><input type='text' class='formtext' name='content' value='<?= isset($data->content) ? $data->content : '' ?>' ></td>
					</tr>
					<tr>
						<td class='formlabel'>TTL(sec):</td>
						<td><input type='text' class='formtext' name='ttl' value='<?= isset($data->ttl) ? $data->ttl : '86400' ?>' ></td>
					</tr>
				<?php else: ?>
					<tr>
						<td class='formlabel'>Название:</td>
						<td><input type='text' name='name' value='<?= isset($data->name) ? $data->name : '' ?>'  /></td>
					</tr>
					<tr>
						<td class='formlabel'>Тип:</td>
						<td><input type='text' name='type' value='<?= isset($data->master) ? $data->master : '' ?>' /></td>
					</tr>
					<tr>
						<td class='formlabel'>Last_check:</td>
						<td><input type='text' class='formtext' name='last_check' value='<?= isset($data->last_check) ?  $data->last_check : '' ?>' ></td>
					</tr>
				<?php endif; ?>
				</table>
				<div class='submit'><div id='sb'></div></div>
		</form>
	<div>
</div>


