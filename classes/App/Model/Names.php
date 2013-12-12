<?php

namespace App\Model;
class Names extends \PHPixie\ORM\Model{

    //Specify which connection to use
    public $connection	= 'itbase';
    public $table		= 'names';
//~
	//~ protected $has_many = array(
							//~ 'controllers'=>array(
									//~ 'model'		 => 'controllers',
									//~ 'through' 	 => 'rights',
									//~ 'foreign_key'=> 'slevel_id',
									//~ 'key'		 => 'control_id'
							//~ ));
    //~ //protected $belongs_to = array('tree');

}
