<?php
namespace App\Model;
 
//PHPixie will guess the name of the table
//from the class name
class Users extends \PHPixie\ORM\Model {

	public $has_many = array('users');
	public $table = 'users';
	public $id = 'users_id';
//~ 
	//~ public function get($property)
	//~ {
		//~ if ($property == 'total_votes') {
			//~ 
			//~ foreach ($this->options->find_all() as $option)
			//~ {
				//~ $options[] = $option->alias;
			//~ }
				//~ return $total;
		//~ }
	//~ }

 
}
