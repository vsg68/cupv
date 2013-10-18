
		<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="entry">
				<thead>
					<tr>
						<th>ФИО</th>
						<th>Mailbox</th>
						<th>Domain</th>
						<th>Passwd</th>
						<th>Nets</th>
						<th>Path</th>
						<th>Groups</th>
						<th>Imap</th>
						<th>Active</th>

					</tr>
				</thead>
				<tbody>
					<?php foreach( $users as $user ): ?>
						<tr id='id-<?= $user->user_id ?>' class='<?= ($user->active == 0) ? 'gradeU': '' ?>'>
							<td id='username-<?= $user->user_id ?>'><?= $user->username ?></td>
							<td id='mailbox-<?= $user->user_id ?>'><?= $user->mailbox ?></td>
							<td class='uneditable'><?= explode('@',$user->mailbox)[1] ?></td>
							<td id='password-<?= $user->user_id ?>'><?= $user->password ?></td>
							<td id='allow_nets-<?= $user->user_id ?>'><?= $user->allow_nets ?></td>
							<td id='path-<?= $user->user_id ?>'><?= $user->path ?></td>
							<td id='acl_groups-<?= $user->user_id ?>'><?= $user->acl_groups ?></td>
							<td id='imap_enable-<?= $user->user_id ?>'><?= $user->imap_enable ?></td>
							<td id='active-<?= $user->user_id ?>'><?= $user->active ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class='divider'>
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="records"></table>
		</div>


