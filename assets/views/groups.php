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
					<?php foreach($entries as $entry): 	?>
						<tr id="tab-groups-<?= $entry->id ?>">
							<td><?= $entry->name ?></td>
							<td><?= $entry->note ?></td>
							<td><?= $entry->active ?></td>
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

			</table>
		</div>
