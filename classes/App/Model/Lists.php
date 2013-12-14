<?php

namespace App\Model;
class Lists extends \PHPixie\ORM\Model{
	public $table='lists';

	protected $belongs_to = array('users' => array(
												'model'	=>'users',
												'key'	=>'user_id'));
}
