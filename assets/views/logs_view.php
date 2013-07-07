<table class='msgblock'>
<?php
		$msgid = $myclass = '';
		foreach ( $messages as $message ):

			if( $msgid != $message->MSGID )
				$myclass = ($myclass == '') ? 'odd':'';
		?>
		<tr class='<?= $myclass ?>'>
			<td><?= $message->ReceivedAt ?></td>
			<td><?= $message->SysLogTag ?></td>
			<td><?= $message->MSGID ?></td>
			<td><?= htmlspecialchars($message->Message)?> </td>
		<tr>
		<?php $msgid = $message->MSGID; ?>
<?php endforeach; ?>
<?php
	if( ! $msgid )
		echo "Поиск результатов не дал...";
?>
</TABLE>

