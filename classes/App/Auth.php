<?php

namespace Auth

class Auth extends \PHPixie\Auth {

  public function build_role($driver, $config) {
			$role_class = '\App\Auth\Role\\'.ucfirst($driver);
			return new $role_class($this->pixie, $config);
		}
}

namespace App\Auth;

class Relation extends \PHPixie\Auth\Role {

  public function has_role($user, $role, $page=0) {

		$relation = $this->relation;
		$field = $this->name_field;

		if($this->type == 'has_many')
			return $user->$relation
					->where($this->name_field, $role)
					//->where($this->page_field, $page)
					->count_all() > 0;

		if ($this->type == 'belongs_to')
			return $user->$relation->$field == $role;

		throw new \Exception("The relationship must be either of has_many or has_one type");
	}


}
