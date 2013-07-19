<?php
return array(
	//~ 'r1' => array('/users(/<action>(/<id>))',
					//~ array(
						//~ 'controller' => 'users',
						//~ 'action' => 'view'
						//~ )),
	//~ 'r2' => array('/aliases(/<action>(/<id>))',
					//~ array(
						//~ 'controller' => 'aliases',
						//~ 'action' => 'view'
						//~ )),
	'default' => array('(/<controller>(/<action>(/<id>)))',
					array(
						'controller' => 'login',
						'action' => 'view'
						)),

);
