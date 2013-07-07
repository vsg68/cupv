<table class='msgblock'>
<?php
		$msgid = '';
		foreach ( $messages as $message ): ?>
		<tr class='<?= ($msgid == $message->MSGID) ? 'odd':'';  ?> <?= $msgid ?>'>
			<td><?= $message->ReceivedAt ?></td>
			<td><?= $message->SysLogTag ?></td>
			<td><?= $message->MSGID ?></td>
			<td><?= htmlspecialchars($message->Message)?> </td>
		<tr>
		<?php $msgid = $message->MSGID; ?>
<?php endforeach; ?>
</TABLE>

