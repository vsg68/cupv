<?php

return array('default' => array(
							//Login providers
							'login' => array(
								'password' => array(
									'login_field' => 'login',
									'password_field' => 'passwd'
									)
								),
							'roles' => array(
									'driver' => 'relation',
									'type' => 'has_many',

									//Field in the roles table
									//that holds the models name
									'name_field' => 'name',
									'relation' => 'roles'
								)
						 )
);
