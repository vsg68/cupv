<?php

namespace App\Model;
class Controllers extends \PHPixie\ORM\Model{

    //Specify which connection to use
    public $connection = 'admin';
	public $table='controllers';

	protected $belongs_to = array('sections' => array(
											'model'	=> 'sections',
											'key'	=> 'section_id'
								));

    //~ protected $has_many = array(
							//~ 'rights'=>array(
									//~ 'model'	=> 'rights',
									//~ 'key'	=> 'control_id'
							//~ ));

	//~ protected $has_many = array(
							//~ 'slevels'=>array(
									//~ 'model'		 => 'slevels',
									//~ 'through' 	 => 'rights',
									//~ 'key'		 => 'slevel_id',
									//~ 'foreign_key'=> 'control_id'
							//~ ));

	protected $has_one = array(
							'rights'=>array(
									'model'	=> 'rights',
									'key'	=> 'control_id'
							));

}
