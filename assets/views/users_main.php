
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
						<tr data='abc' id='id-<?= $user->id ?>' class='<?= ($user->active == 0) ? 'gradeU': '' ?>'>
							<td><?= $user->username ?></td>
							<td><?= $user->mailbox ?></td>
							<td><?= explode('@',$user->mailbox)[1] ?></td>
							<td><?= $user->password ?></td>
							<td><?= $user->allow_nets ?></td>
							<td><?= $user->path ?></td>
							<td><?= $user->acl_groups ?></td>
							<td><input type='checkbox' disabled <?php ($user->imap_enable) && print('checked') ?>></td>
							<td><input type='checkbox' disabled <?php ($user->active ) && print('checked')?>></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class='divider'>
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="records">
				<thead>
					<tr>
						<th>Alias</th>
						<th>Forward</th>
						<th>on/off</th>
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


