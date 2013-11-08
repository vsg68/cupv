<div class='user-form ui-widget ui-corner-all'>


<table class='table-grp' id='pid-<?= $pid ?>'>
	<tr>
		<th>Доступные группы</th>
		<th></th>
		<th>Задействованные</th>
	</tr>
	<tr>
		<td class='ui-widget-content ui-corner-all'>
			<div class='ui-window'>
			<ul id='grp-left' class='nest-grp'>
			<?php foreach( $groups as $group) {
					if( ! isset($group->users_id) ):
			?>
				<li id="gr-<?= $group->id ?>"><span title="<?= $group->note ?>"><?= $group->name ?></span></li>

			<?php endif;
					}
			?>
            </ul>
            </div>
         </td>
         <td>
			 <div id='arrow-left' class='image-arrow disable-arrow'></div>
			 <div id='arrow-right' class='image-arrow disable-arrow'></div>
		</td>
		<td class='ui-window ui-widget-content ui-corner-all'>
			<div class='ui-window'>
			<ul id='grp-right' class='nest-grp'>
			<?php foreach( $groups as $group) {
					if( isset($group->users_id) ):
			?>
				<li id="gr-<?= $group->id ?>"><span title="<?= $group->note ?>"><?= $group->name ?></span></li>

			<?php endif;
					}
			?>
            </ul>
            </div>
		</td>
	</tr>
</table>
<form id='usersform' action='#' method='post'></form>
<div class='submit'><div id='sb'></div></div>
</div>
