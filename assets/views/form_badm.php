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
			   <table>
					<tr>
						<td class='formlabel'>Название:</td>
						<td>
							<input type='hidden' name='fname[]' value='Название'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						</td>
					</tr>
					<tr>
						<td class='formlabel'>Модель:</td>
						<td>
							<input type='hidden' name='fname[]' value='Модель'/>
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
						<td class='formlabel'>Логин:</td>
						<td>
							<input type='hidden' name='fname[]' value='Логин'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						<td>
					</tr>
					<tr>
						<td class='formlabel'>Пароль:</td>
						<td>
							<input type='hidden' name='fname[]' value='Пароль'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						<td>
					</tr>
					<tr>
						<td class='formlabel'>Enable:</td>
						<td>
							<input type='hidden' name='fname[]' value='Enable'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						<td>
					</tr>
					<tr>
						<td class='formlabel'>Доступ(протокол):</td>
						<td>
							<input type='hidden' name='fname[]' value='Доступ(протокол)'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						<td>
					</tr>
					<tr>
						<td class='formlabel'>Расположение:</td>
						<td>
							<input type='hidden' name='fname[]' value='Расположение'/>
							<input type='hidden' name='ftype[]' value='text'/>
							<input type='text' name='fval[]'/>
						<td>
					</tr>
					<tr>
						<td class='formlabel'>Описание:</td>
						<td>
							<input type='hidden' name='fname[]' value='Описание'/>
							<input type='hidden' name='ftype[]' value='textarea'/>
							<textarea name='fval[]'/></textarea>
						<td>
					</tr>


			  </table>
				<div class='submit'><div id='sb'></div></div>
		</form>
	<div>
</div>


