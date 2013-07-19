<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' checked>
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Домен:</span>
			<input class='formtext' type='text' name='domain_name' value='' placeholder='domain.name'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='domain_notes' value=''  placeholder='text' />
	   </div>
	   <div class='fieldentry path'>
			 <span class='formlabel'>Транспорт:<span id='path' class='web' >&rarr;</span></span>

		</div>
	   <div class='fieldentry listbox'>
			<span class='formlabel'>Рассылка:<span id='all_email' class='web'>&rarr;</span></span>

	   </div>
<br />
		<h4>Алиасы домена</h4>
		<table class='atable'>
			<tr><th  class='txt'>alias</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
		</table>

	<div class='submit'><input type='submit' id='submit_domain' value='Изменить'></div>
</form>

