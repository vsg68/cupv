		<div class='vertical-50'>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="tab-domains">
					<thead>
						<tr>
							<th>Домен</th>
							<th>Описание</th>
							<th>Адрес рассылки</th>
							<th>Включение рассылки</th>
							<th>Active</th>
						</tr>
					</thead>
						<tbody>
							<?php
								foreach($entries as $entry) {
									if($entry->domain_type == 0):
							?>
							<tr id="tab-domains-<?= $entry->id ?>">
								<td><?= $entry->domain_name ?></td>
								<td><?= $entry->domain_notes ?></td>
								<td><?= $entry->all_email ?></td>
								<td><?= $entry->all_enable ?></td>
								<td><?= $entry->active ?></td>
							</tr>
							<?php endif; } ?>
						</tbody>
				</table>
			</div>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="tab-aliases">
					<thead>
						<tr>
							<th>Домен</th>
							<th>Чей алиас</th>
							<th>Описание</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($entries as $entry) {
								if($entry->domain_type == 1):
						?>
						<tr id="tab-aliases-<?= $entry->id ?>">
							<td><?= $entry->domain_name ?></td>
							<td><?= $entry->delivery_to ?></td>
							<td><?= $entry->domain_notes ?></td>
							<td><?= $entry->active ?></td>
						</tr>
						<?php  endif; } ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class='vertical-50 right-side'>
			<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="tab-transport">
				<thead>
					<tr>
						<th>Домен</th>
						<th>Описание</th>
						<th>Адрес пересылки</th>
						<th>Active</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($entries as $entry) {
							if($entry->domain_type == 2):
					?>
					<tr id="tab-transport-<?= $entry->id ?>">
						<td><?= $entry->domain_name ?></td>
						<td><?= $entry->domain_notes ?></td>
						<td><?= $entry->delivery_to ?></td>
						<td><?= $entry->active ?></td>
					</tr>
					<?php endif; } ?>
				</tbody>
			</table>
		</div>


