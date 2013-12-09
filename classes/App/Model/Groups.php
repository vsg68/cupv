<?php

namespace App\Model;
class Groups extends \PHPixie\ORM\Model{
	public $table='groups';

	protected $has_many = array(
						'users'=> array(
								'model'		 =>'users',
								'through'	 =>'lists',
								'key'		 =>'group_id',
								'foreign_key'=>'user_id'
								));
	protected $has_one = array(
						'lists'=> array(
								'model'	=>'lists',
								'key'	=>'group_id'
								));
}
