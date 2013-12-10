<?php

namespace App\Model;
class Slevels extends \PHPixie\ORM\Model{

    //Specify which connection to use
    public $connection	= 'admin';
    public $table		= 'slevels';

	protected $has_many = array(
							'controllers'=>array(
									'model'		 => 'controllers',
									'through' 	 => 'rights',
									'foreign_key'=> 'slevel_id',
									'key'		 => 'control_id'
							));
    //protected $belongs_to = array('tree');

}
