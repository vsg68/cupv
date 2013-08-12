<?php

return array(
	'default' => array(
		'user'=>'mailserver',
		'password' => 'boo1aKeisoot',
		'driver' => 'Mysql',
		'db' => 'mailserver',
		'host'=>'localhost'
	),
	'logs' => array(
		'host'=>'localhost',
		'user'=>'logger',
		'password' => 'FibieNg1uT2p',
		'driver' => 'Mysql',
		'db' => 'logs',
	),
	'login' => array(
		'user'=>'mailserver',
		'password' => 'boo1aKeisoot',
		'driver' => 'PDO',
		'connection'=>'mysql:host=localhost;dbname=mailserver'
	)

);
