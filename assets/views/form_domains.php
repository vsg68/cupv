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
					<td class='formlabel'>Домен:</td>
					<td><input class='ui-widget-content ui-corner-all' type='text' name='domain_name' value='<?= isset($data->domain_name) ? $data->domain_name : '' ?>'  /></td>
				</tr>
				<tr>
					<td class='formlabel'>Описание:</td>
					<td><input type='text' name='domain_notes' value='<?= isset($data->domain_notes) ? $data->domain_notes : '' ?>'></td>
				</tr>
			<?php if($tab == 'domains'): ?>
				<input type='hidden' name='delivery_to' value='virtual' />
				<input type='hidden' name='domain_type' value='0' />
				<tr>
					<td class='formlabel'>Адрес рассылки:</td>
					<td><input class='login' type='text' name='all_email' value='<?= isset($data->all_email) ? explode('@',$data->all_email)[0] : '' ?>' />&nbsp;<strong>@</strong>

						<?php if( isset($data->all_email) ): ?>
							<select class='ui-widget-content ui-corner-all' name='domain'>
								<?php foreach( $domains as $domain): ?>
								<option value='<?= $domain->domain_name ?>' <?= (( isset($data->domain_name) ? $data->domain_name : 'gmpro.ru') == $domain->domain_name) ? 'selected': '' ?> ><?= $domain->domain_name ?></option>
								<?php endforeach; ?>
							</select>
						<?php else: ?>
							<strong>domain.name</strong>
						<?php endif; ?>

					</td>
				</tr>
				<tr>
					<td class='formlabel'>Включение рассылки:</td>
					<td><input type='checkbox' class='formtext' name='all_enable' value='1' <?php isset($data->all_enable) ? ($data->all_enable & 1) && print('checked') : '' ?> ></td>
				</tr>
			<?php endif; ?>
			<?php if($tab == 'aliases'): ?>
				<tr>
					<td class='formlabel'>Реальный домен:</td>
					<td>
						<select class='ui-widget-content ui-corner-all' name='delivery_to'>
							<?php foreach( $domains as $domain): ?>
							<option value='<?= $domain->domain_name ?>' <?= (( isset($data->domain_name) ? $data->domain_name : 'gmpro.ru') == $domain->domain_name) ? 'selected': '' ?> ><?= $domain->domain_name ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<input type='hidden' name='domain_type' value='1' />
			<?php endif; ?>
			<?php if($tab == 'transport'): ?>
				<tr>
					<td class='formlabel'>Протокол:[Адрес] пересылки</td>
					<td><input type='text' name='delivery_to' value='<?= isset($data->delivery_to) ? $data->delivery_to : '' ?>'  /></td>
				</tr>
				<input type='hidden' name='domain_type' value='2' />
			<?php endif; ?>
				<tr>
					<td class='formlabel'>Активно:</td>
					<td><input type='checkbox' class='formtext' name='active' value='1' <?php isset($data->active) ?  ($data->active & 1) && print('checked') : print('checked') ?> ></td>
				</tr>
			</table>
			<div class='submit'><div id='sb'></div></div>

			</div>
	</form>
	</div>

<div>

