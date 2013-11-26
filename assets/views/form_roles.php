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
				   	<?php if( $pid ): ?>
					<tr>
						<input type='hidden' name='role_id' value='<?= $pid  ?>'  />
						<input type='hidden' name='ctrl' value='<?= $ctrl  ?>'  />
						<td class='formlabel'>Раздел:</td><td class='section'></td>
					</tr>
					<tr>
						<td class='formlabel'>Страница:</td><td class='control'></td>
					</tr>
					<tr>
						<td class='formlabel'>Права:</td>
						<td>
							<?php foreach( $slevels as $slevel ): ?>
							<input type='radio' name='slevel_id' value='<?= $slevel->id ?>' <?= ($slevel->id == ( isset($data->slevel_id) ? $data->slevel_id : 1) ? ' checked' : '') ?>/> <?= $slevel->name ?>
							<?php endforeach; ?>
						</td>
					</tr>
					<?php else: ?>
					<tr>
						<td class='formlabel'>Роль:</td>
						<td><input type='text' name='name' value='<?= isset($data->name) ? $data->name : '' ?>'  /></td>
					</tr>
					<tr>
						<td class='formlabel'>Описание:</td>
						<td><input type='text' name='note' value='<?= isset($data->note) ? $data->note : '' ?>' /></td>
					</tr>
					<tr>
						<td class='formlabel'>Активно:</td>
						<td><input type='checkbox' class='formtext' name='active' value='1' <?php isset($data->active) ?  ($data->active & 1) && print('checked') : print('checked') ?> ></td>
					</tr>
					<?php endif; ?>
				</table>
				<div class='submit'><div id='sb'></div></div>
		</form>
	<div>
</div>


