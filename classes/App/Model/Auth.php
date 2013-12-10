<?php

namespace App\Model;
class Auth extends \PHPixie\ORM\Model{

	public $connection	= 'admin';
	public $table		= 'auth';

	protected $belongs_to = array('roles' => array(
												'model'	=> 'roles',
												'key'	=> 'role_id'
												));
}
