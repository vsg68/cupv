
	<div class='gorizont-50'>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-dns">
			<thead>
				<tr>
					<th>Название</th>
					<th>Тип</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($entries as $entry): 	?>
				<tr id="tab-dns-<?= $entry->id ?>">
					<td><?= $entry->name ?></td>
					<td><?= $entry->master ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class='gorizont-50'>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-records">
			<thead>
				<tr>
					<th>Hostname</th>
					<th>Type</th>
					<th>name / ip</th>
					<th>TTL</th>
				</tr>
			</thead>
		</table>
	</div>
