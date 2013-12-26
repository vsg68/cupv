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
			<input type='hidden' name='pid' value='<?= $pid ?>'  />
			<h4></h4>
			   <table id='tab-rec'>
					<tr>
						<td class='formlabel'>Название:</td>
						<td>
							<input type='hidden' name='fname[]' value='Название'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						</td>
					</tr>
					<tr>
						<td class='formlabel'>Адрес:</td>
						<td>
							<input type='hidden' name='fname[]' value='Адрес'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						</td>
					</tr>
					<tr>
						<td class='formlabel'>№ Договора:</td>
						<td>
							<input type='hidden' name='fname[]' value='№ Договора'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						<td>
					</tr>
					<tr>
						<td class='formlabel'>Дата договора:</td>
						<td>
							<input type='hidden' name='fname[]' value='Дата договора'/>
							<input type='hidden' name='ftype[]' value='date'/>
							<input type='text' name='fval[]' class='date_field' />
						<td>
					</tr>
					<tr>
						<td class='formlabel'>Реквизиты:</td>
						<td>
							<input type='hidden' name='fname[]' value='Реквизиты'/>
							<input type='hidden' name='ftype[]' value='textarea'/>
							<textarea name='fval[]'></textarea>
						<td>
					</tr>
					<tr>
						<td class='formlabel'>Описание:</td>
						<td>
							<input type='hidden' name='fname[]' value='Описание'/>
							<input type='hidden' name='ftype[]' value='textarea'/>
							<textarea name='fval[]'></textarea>
						<td>
					</tr>


			  </table>

				<div class='submit'><div id='sb'></div></div>
		</form>
	<div>
</div>


