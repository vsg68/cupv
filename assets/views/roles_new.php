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
		<ul class='atable accordion'>
		<?php
			$sect='';
			$is_change = 1;
			foreach($pages as $page) {

				if($sect != $page->sect_name) {

					$sect = $page->sect_name;

					if( $is_change = ($is_change) ? 0 : 1 )
						echo "</div></li>";
		?>
			<li><h3><span class='ptr'></span><?= $page->sect_name ?></h3>
				<div class='panel'>
					<div class='fieldentry head'><span class='formlabel'>страница</span><span class='formlabel'>права</span></div>
		<?php
				} // enfif
		?>
					<div class='fieldentry'>
						<span class='formlabel'><?= $page->ctrl_name ?></span>
						<div class='fortext'>
							<input type='hidden' name='page[]' value='<?= $page->ctrl_id ?>'>

							<?php foreach($slevels as $slevel): ?>
							<input type='radio' name='p-<?= $page->ctrl_id ?>' value='<?= $slevel->id ?>' <?php ( ! $slevel->slevel) && print 'checked' ?>><?= $slevel->name ?>
							<?php endforeach; ?>

						</div>
					</div>
		<?php

			} // endforeach
		 ?>
				</div>
			</li>
		</ul>

	<div class='submit'><input type='submit' id='submit_roles' value='Изменить'></div>
</form>

<?= $addscript ?>
