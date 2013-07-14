<?php
namespace App;

class Page extends \PHPixie\Controller {

	protected $view;
	protected $auth;

	public function before() {

		 $this->view = $this->pixie->view('main');
	}


	protected function logged_in($role = null){

        if($this->pixie->auth->user() == null) {
            $this->redirect('/login');
            return false;
        }

        if($role && !$this->pixie->auth->has_role($role)){
            $this->response->body = "You don't have the permissions to access this page";
            $this->execute=false;
            return false;
        }

        return true;
    }
}
