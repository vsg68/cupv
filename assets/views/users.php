
		<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-users">
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
						<tr id='tab-users-<?= $user->id ?>' class='<?= ($user->active == 0) ? 'gradeUU': '' ?>'>
							<td><?= $user->username ?></td>
							<td><?= $user->mailbox ?></td>
							<td><?= explode('@',$user->mailbox)[1] ?></td>
							<td><?= $user->password ?></td>
							<td><?= $user->allow_nets ?></td>
							<td><?= $user->path ?></td>
							<td><?= $user->acl_groups ?></td>
							<td><?= $user->imap_enable ?></td>
							<td><?= $user->active ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class='divider'>
			<div class='divider-70'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-aliases">
					<thead>
						<tr>
							<th>Alias</th>
							<th>Forward</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class='divider-30'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-lists">
					<thead>
						<tr><th>список рассылки</th></tr>
					</thead>
					<tbody>
						<tr><td></td></tr>
					</tbody>
				</table>
			</div>
		</div>


