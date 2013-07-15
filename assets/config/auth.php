<?php

return array('default' => array(
							'model' => 'login',
							//Login providers
							'login' => array(
								'Password' => array(
									'login_field' => 'login',
									'password_field' => 'passwd',
								//	'hash_method'	=> ''
									)
								),
							'roles' => array(
									'driver' => 'relation',
									'type' => 'belongs_to',
									'name_field' => 'role_name',
									'relation' => 'role'
								)
						 )
);
