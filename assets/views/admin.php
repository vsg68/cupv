		<div class='vertical-50'>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-sections">
					<thead>
						<tr>
							<th>Раздел</th>
							<th>Описание</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($entries as $entry): 	?>
						<tr id="tab-sections-<?= $entry->id ?>">
							<td><?= $entry->name ?></td>
							<td><?= $entry->note ?></td>
							<td><?= $entry->active ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-controllers">
					<thead>
						<tr>
							<th>N</th>
							<th>Название страницы</th>
							<th>Контроллер</th>
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
						<th>Контроллер</th>
						<th>Страница</th>
						<th>Раздел</th>
						<th>Active</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>


