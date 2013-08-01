<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивна:</span>
			<input type='checkbox' class='formtext' name='active' value='1' checked>
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Роль:</span>
			<input class='formtext' type='text' name='domain_name' value='' placeholder='domain.name'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='domain_notes' value=''  placeholder='text' />
	   </div>

<br />
		<h4>Права на страницы</h4>
		<table class='atable'>
			<tr><th  class='txt1'>страница</th><th>права</th></tr>
			<?php foreach($pages as $page): ?>
			<tr>
				<td class='txt1'><?= $page->name ?></td>
				<td>
					<input type='radio' name='p-<?= $page->id ?>' value='0' checked>None
					<input type='radio' name='p-<?= $page->id ?>' value='1'>Read
					<input type='radio' name='p-<?= $page->id ?>' value='2'>Write
					<input type='hidden' name='page[]' value='<?= $page->id ?>'>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>

	<div class='submit'><input type='submit' id='submit_domain' value='Изменить'></div>
</form>

