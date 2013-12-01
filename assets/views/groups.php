<div class='vertical-50'>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-groups">
					<thead>
						<tr>
							<th>Группа</th>
							<th>Описание</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($groups as $group): 	?>
						<tr id="tab-groups-<?= $group['id'] ?>">
							<td><?= $group['name'] ?></td>
							<td><?= $group['note'] ?></td>
							<td><?= $group['active'] ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-lists">
					<thead>
						<tr>
							<th>Пользователь</th>
							<th>mailbox</th>
							<th>Active</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<div class='vertical-50 right-side'>
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-full">
				<thead>
					<tr>
						<th>Группа</th>
						<th>Пользователь</th>
						<th>mailbox</th>
						<th>Active</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($entries as $entry): 	?>
						<tr id="tab-full-<?= $entry->lid ?>" class='gradeA'>
							<td><?= $entry->name ?></td>
							<td><?= $entry->username ?></td>
							<td><?= $entry->login ?></td>
							<td><?= $entry->g_active ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
