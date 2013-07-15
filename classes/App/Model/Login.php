<?php

namespace App\Model;
class Login extends \PHPixie\ORM\Model{


	//public $id_field='id';

    //Specify table name
    public $table='auth';

    //Specify which connection to use
    public $connection = 'login';

    //If the fairy belongs to a single role
    //you can use the belongs_to relationship
    protected $belongs_to = array('role');
    //If you want each fairy to have multiple
    //roles you need to use the many-to-many relationship
    //~ protected $has_many = array('roles'=>array(
											//~ 'key'=>'id'
										//~ ));
}
