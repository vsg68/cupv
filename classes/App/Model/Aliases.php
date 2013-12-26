<?php

namespace App\Model;
class Aliases extends \PHPixie\ORM\Model{
	public $table='aliases';

	protected $belongs_to = array('users' => array(
												'model'	=>'users',
												'key'	=>'mailbox'));
}
