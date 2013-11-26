			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-roles">
					<thead>
						<tr>
							<th>Роль</th>
							<th>Описание</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($entries as $entry): 	?>
						<tr id="tab-roles-<?= $entry->id ?>">
							<td><?= $entry->name ?></td>
							<td><?= $entry->note ?></td>
							<td><?= $entry->active ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-rights">
					<thead>
						<tr>
							<th>Раздел</th>
							<th>Страница</th>
							<th>Права</th>
							<th>Active</th>
						</tr>
					</thead>
				</table>
			</div>



