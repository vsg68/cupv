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
				   	<?php if( isset($options)): ?>
				   	<input type='hidden' name='order' value='<?= (isset($data->order) ? $data->order : $count ) ?>'  />
					<tr>
						<td class='formlabel noname'>Страница:</td>
						<td><input type='text' name='name' value='<?= isset($data->name) ? $data->name : '' ?>'  /></td>
					</tr>
					<tr>
						<td class='formlabel'>Контроллер:</td>
						<td>
							<input type='hidden' name='section_id' value='<?= (isset($data->section_id) ? $data->section_id : $pid ) ?>'  />
							<select name='class'>
								<?php foreach( $options as $opt): ?>
								<option value='<?= $opt ?>' <?= (( isset($data->class) ? $data->class : '') == $opt) ? 'selected': '' ?> ><?= ucfirst($opt) ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<?php else: ?>
					<tr>
						<td class='formlabel noname'>Раздел:</td>
						<td><input type='text' name='name' value='<?= isset($data->name) ? $data->name : '' ?>'  /></td>
					</tr>
					<tr>
						<td class='formlabel'>Описание:</td>
						<td><input type='text' name='note' value='<?= isset($data->note) ? $data->note : '' ?>' /></td>
					</tr>
					<?php endif; ?>
					<tr>
						<td class='formlabel'>Активно:</td>
						<td><input type='checkbox' class='formtext' name='active' value='1' <?php isset($data->active) ?  ($data->active & 1) && print('checked') : print('checked') ?> ></td>
					</tr>

				</table>
				<div class='submit'><div id='sb'></div></div>
		</form>
	<div>
</div>


