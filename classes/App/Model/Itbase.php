<?php

namespace App\Model;
class Itbase extends \PHPixie\ORM\Model{

    //Specify which connection to use
    public $connection	= 'itbase';
    public $table		= 'entries';

   protected $has_one=array(
        'strings'=>array(
			            'model'=>'itemdata',
			            'key'=>'pid'
			        )
    );
}
