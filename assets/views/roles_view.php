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
				} // endif
		?>
					<div class='fieldentry'>
						<span class='formlabel'><?= $page->ctrl_name ?></span>
						<div class='fortext'>
							<input type='hidden' name='page[]' value='<?= $page->control_id ?>'>

							<?php foreach($slevels as $slevel): ?>
							<input type='radio' name='p-<?= $page->control_id ?>' value='<?= $slevel->id ?>' <?php ( $slevel->id == $page->slevel_id) && print 'checked' ?>><?= $slevel->name ?>
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

