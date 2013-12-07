<?php

namespace App\Model;
class Controllers extends \PHPixie\ORM\Model{

    //Specify which connection to use
    public $connection = 'admin';
	public $table='controllers';



    protected $has_many = array(
							'rights'=>array(
									'model'=>'rights',
									'key'=>'control_id'
							));

}
