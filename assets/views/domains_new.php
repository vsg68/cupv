<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' checked>
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Домен:</span>
			<input class='formtext' type='text' name='domain_name' value=''  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='domain_notes' value=''  />
	   </div>
	   <div class='fieldentry'>
			 <span class='formlabel'>Транспорт:</span>
			 <button id='path' class='web' >4</button>
		</div>

		<div class='submit'><input type='submit' id='submit_domain' value='Изменить'></div>
</form>

