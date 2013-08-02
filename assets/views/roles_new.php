<div id='log'><?= $log ?></div>
<form id='usersform' action='#' method='post'>
		<div class='fieldentry'>
			<span class='formlabel'>Aктивна:</span>
			<input type='checkbox' class='formtext' name='active' value='1' checked>
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Роль:</span>
			<input class='formtext' type='text' name='role_name' value='' placeholder='название'  />
	   </div>
	   <div class='fieldentry'>
			<span class='formlabel'>Описание:</span>
			<input  class='formtext' type='text' name='role_notes' value=''  placeholder='описание' />
	   </div>

<br />
		<h4>Права на страницы</h4>
		<ul class='atable'>
			<?php $sect=''; foreach($pages as $page): ?>
			<?php if($sect != $page->sect_name): ?>
			<li><h3><?= $page->sect_name ?></h3>
				<div class='fieldentry head'><span class='formlabel'>страница</span><span class='formlabel'>права</span></div>
			<?php endif; ?>
				<div class='fieldentry'>
					<span class='formlabel'><?= $page->ctrl_name ?></span>
					<div class='fortext'>
						<input type='hidden' name='page[]' value='<?= $page->ctrl_id ?>'>
						<?php foreach($slevels as $slevel): ?>
						<input type='radio' name='p-<?= $page->ctrl_id ?>' value='<?= $slevel->id ?>' <?= ( ! $slevel->slevel)|| print 'checked' ?>><?= $slevel->name ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php $sect = $page->sect_name; ?>
			</li>
			<?php endforeach; ?>
		</ul>

	<div class='submit'><input type='submit' id='submit_roles' value='Изменить'></div>
</form>

