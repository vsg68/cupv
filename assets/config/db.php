<?php

return array(
	'default' => array(
		'user'=>'root',
		'password' => '',
		//'password' => 'boo1aKeisoot',
		'driver' => 'Mysql',

		//'Connection' is required if you use the PDO driver
		//'connection'=>'mysql:host=localhost;dbname=ms',

		// 'db' and 'host' are required if you use Mysql driver
		'db' => 'ms',
		'host'=>'localhost'
	),
	'logs' => array(
		'host'=>'localhost',
		'user'=>'root',
		'password' => '',
		'driver' => 'Mysql',
		'db' => 'logs',
	),
	'login' => array(
		'user'=>'root',
		'password' => '',
		'driver' => 'PDO',
		'connection'=>'mysql:host=localhost;dbname=ms',
	)
);
