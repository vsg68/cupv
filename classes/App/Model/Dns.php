<?php

namespace App\Model;
class Dns extends \PHPixie\ORM\Model{

    //Specify which connection to use
    public $connection = 'dns';
	public $table='domains';

    protected $has_many = array(
							'records'=>array(
									'model'=>'records',
									'key'=>'domain_id'
								));

}
