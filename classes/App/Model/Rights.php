<?php

namespace App\Model;
class Rights extends \PHPixie\ORM\Model{

	public $connection	= 'admin';
	public $table		= 'rights';

	protected $belongs_to = array('slevels' => array(
												'model'	=> 'slevels',
												'key'	=> 'slevel_id'
												));
}
