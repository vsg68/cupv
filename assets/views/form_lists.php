<div class='user-form ui-widget ui-corner-all'>


<table class='table-grp' id='pid-<?= (isset($pid) ? $pid : '') ?>'>
	<tr>
		<th>Доступные</th>
		<th></th>
		<th>Задействованные</th>
	</tr>
	<tr>
		<td class='ui-widget-content ui-corner-all'>
			<div class='ui-window'>
			<ul id='grp-left' class='nest-grp'>
			<?php // раздел users -> groups
				 if(isset($entries) ) {
					foreach( $entries as $entry) {

						if( $entry->lists->group_id != $pid ) {
							echo '<li id="gr-'. $entry->id .'"><span title="'. $entry->mailbox .'">'. $entry->username .'</span></li>';
						}
					}
				 }
				// раздел groups -> users
				 elseif(isset($rows) ) {
					 foreach( $rows as $row) {
						if( $row->user_id != $pid ) {
							echo '<li id="gr-'. $row->id .'"><span title="'. $row->note  .'">'. $row->name .'</span></li>';
						}
					}
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
			<?php // раздел users -> groups
				 if(isset($entries) ) {
					foreach( $entries as $entry) {

						if( $entry->lists->group_id == $pid ) {
							echo '<li id="gr-'. $entry->id .'"><span title="'. $entry->mailbox .'">'. $entry->username .'</span></li>';
						}
					}
				 }
				// раздел groups -> users
				 elseif(isset($rows) ) {
					 foreach( $rows as $row) {
						if( $row->user_id == $pid ) {
							echo '<li id="gr-'. $row->id .'"><span title="'. $row->note  .'">'. $row->name .'</span></li>';
						}
					}
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
