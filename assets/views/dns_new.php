<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
	<h4>Новая зона.</h4>
	   <div class='fieldentry'>
			 <span class='formlabel' title='Создать обратную зону'>Reverse:</span>
			 <input type='checkbox' class='formtext' name='is_reverse' value='1' checked >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel' title='Имя зоны'>Название зоны:</span>
			<input class='formtext' type='text' name='zname' placeholder='zone.name'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel' title='admin E-MAIL'>Контакт:</span>
			<input class='formtext' type='text' name='contact' placeholder='admin@zone.name'  />
	   </div>
	    <div class='fieldentry'>
			<span class='formlabel' title='время обновления записи'>TTL:</span>
			<input class='formtext' type='text' name='ttl' value='86400' />
	   </div>
		<div class='fieldentry'>
			<span class='formlabel' title='nameserver'>NS1:</span>
			<input class='formtext' type='text' name='ns2' placeholder='host name' />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel' title='nameserver'>NS2:</span>
			<input class='formtext' type='text' name='ns1' placeholder='host name' />
	   </div>
	   <h4>Записи</h4>

		<table class='atable'>
				<tr><th class='txt'>name</th><th>type</th><th class='txt'>IP</th><th class='else'><div id='alias' title='Добавить'></div></th></tr>
				<tr><td><input type="text" name="fname[]" placeholder="NS1 name"></td>
					<td>
						<select name="ftype[]">
							<option value="NS">NS
							<option value="A" selected>A
							<option value="MX">MX
							<option value="CNAME">CNAME
							<option value="TXT">TXT
						</select>
					</td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS1"></td>
					<td>
						<input type="hidden" name="stat[]" value="1">
						<input type="hidden" name="fid[]" value="0">
						<div class="delRow"></div>
					</td>
				</tr>
				<tr><td><input type="text" name="fname[]" placeholder="NS2 name"></td>
					<td>
						<select name="ftype[]">
							<option value="NS">NS
							<option value="A" selected>A
							<option value="MX">MX
							<option value="CNAME">CNAME
							<option value="TXT">TXT
						</select>
					</td>
					<td><input type="text" name="faddr[]" placeholder="IP адрес NS2"></td>
					<td>
						<input type="hidden" name="stat[]" value="1">
						<input type="hidden" name="fid[]" value="0">
						<div class="delRow"></div>
					</td>
				</tr>

		</table>


	<div class='submit'><input type='submit' id='submit_view' value='Добавить'></div>
</form>

