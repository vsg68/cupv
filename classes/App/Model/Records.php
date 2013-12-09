<?php

namespace App\Model;
class Records extends \PHPixie\ORM\Model{

    //Specify which connection to use
    public $connection = 'dns';
	public $table='records';

    protected $belongs_to = array('domain');

}
