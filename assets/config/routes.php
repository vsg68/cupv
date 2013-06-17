<?php
return array(
	'r1' => array('/users(/<action>(/<id>))',
					array(
						'controller' => 'users',
						'action' => 'index'
						)),
	'r2' => array('/aliases(/<action>(/<id>))',
					array(
						'controller' => 'aliases',
						'action' => 'view'
						)),
	'default' => array('(/<controller>(/<action>))',
					array(
						'controller' => 'users',
						'action' => 'index'
						)),
);
