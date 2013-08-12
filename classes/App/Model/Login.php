<?php

namespace App\Model;
class Login extends \PHPixie\ORM\Model{


    //Specify table name
    public $table='auth';

    //Specify which connection to use
    public $connection = 'login';

}
