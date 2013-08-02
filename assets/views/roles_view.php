<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>

	<h4><?= $role->name ?></h4>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивен:</span>
			<input type='checkbox' class='formtext' name='active' value='1' <?php ($role->active == 1) && print ('checked'); ?> >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Название:</span>
			<input type='text' class='formtext' name='role_name' value='<?= $role->name ?>' >
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='role_notes' value='<?= $role->notes; ?>'  />
	   </div>
		<input type='hidden' name='role_id' value='<?= $role->id; ?>'  />
<br />
		<h4>Права на страницы</h4>
		<table class='atable'>
			<tr><th  class='txt1'>страница</th><th>права</th></tr>
			<?php foreach($pages as $page): ?>
			<tr>
				<td class='txt1'><?= $page->name ?></td>
				<td>
					<input type='hidden' name='page[]' value='<?= $page->control_id ?>'>
					<?php foreach($slevels as $slevel): ?>
					<input type='radio' name='p-<?= $page->control_id ?>' value='<?= $slevel->id ?>' <?php ($slevel->id == $page->slevel_id) && print ' checked' ?>><?= $slevel->name ?>
					<?php endforeach; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>

	<div class='submit'><input type='submit' id='submit_roles' value='Изменить'></div>
</form>

