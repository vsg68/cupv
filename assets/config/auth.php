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

									//Field in the roles table
									//that holds the models name
									'name_field' => 'role_name',
									'relation' => 'role'
								)
						 )
);
