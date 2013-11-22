		<div class='vertical-50'>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="tab-sections">
					<thead>
						<tr>
							<th>Раздел</th>
							<th>Описание</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$old_id = 0;
						foreach($entries as $entry) {
							if( $old_id == $entry->s_id || (! $entry->s_id) )
								continue;
					?>
						<tr id="tab-sections-<?= $entry->s_id ?>">
							<td><?= $entry->s_name?></td>
							<td><?= $entry->s_note?></td>
							<td><?= $entry->s_active?></td>
						</tr>
					<?php
							$old_id = $entry->s_id; }
					?>
					</tbody>
				</table>
			</div>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="tab-controllers">
					<thead>
						<tr>
							<th>Название страницы</th>
							<th>Контроллер</th>
							<th>Active</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<div class='vertical-50 right-side'>
			<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="tab-full">
				<thead>
					<tr>
						<th>Раздел</th>
						<th>Страница</th>
						<th>Контроллер</th>
						<th>Active</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($entries as $entry): ?>
						<tr id="s<?= $entry->s_id ?>-c<?= $entry->c_id ?>" class="gradeA">
							<td><?= $entry->s_name?></td>
							<td><?= $entry->c_name?></td>
							<td><?= $entry->c_class?></td>
							<td><?= $entry->c_active?></td>
						</tr>
					<?php endforeach; ?>

				</tbody>
			</table>
		</div>


