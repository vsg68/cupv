<?php
namespace App\Model;
 
//PHPixie will guess the name of the table
//from the class name
class Users extends \PHPixie\ORM\Model {

	protected $has_many = array('aliases' => array(
												'model' => 'aliases',
												'key' => 'alias_name'
											));
	public $table = 'users';
	public $id_field = 'user_id';
//~ 
	public function get($property)
	{
		if ($property == 'ee') {
			
			//~ foreach ($this->options->find_all() as $option)
			//~ {
				//~ $options[] = $option->alias;
			//~ }
				return 'ee';
		}
	}

 
}
