
		<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-aliases">
				<thead>
					<tr>
						<th>От кого</th>
						<th></th>
						<th>Кому</th>
						<th>Alias</th>
						<th>Delivery_to</th>
						<th>Note</th>
						<th>Active</th>

					</tr>
				</thead>
				<tbody>
					<?php foreach( $entries as $entry ): ?>
						<tr id='tab-aliases-<?= $entry->id ?>' class='<?= ($entry->active == 0) ? 'gradeUU': '' ?>'>
							<td><?= $entry->from_username ?></td>
							<td><?= $entry->direction ?></td>
							<td><?= $entry->to_username ?></td>
							<td><?= $entry->alias_name ?></td>
							<td><?= $entry->delivery_to ?></td>
							<td><?= $entry->alias_notes ?></td>
							<td><?= $entry->active ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>



