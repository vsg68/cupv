<pre class='msgblock'>
<?php
	foreach ( $messages as $message )
		echo $message->ReceivedAt." ".$message->SysLogTag." ".htmlspecialchars($message->Message)."\n";
?>
</pre>

