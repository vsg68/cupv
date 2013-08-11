<?php

namespace App\Controller;

class Backup extends \App\Page {

   private $role_id;


    public function action_view() {


		$this->view->subview 		= 'backup_main';

		$this->view->script_file	= '';
		$this->view->css_file 		= '';

	    $this->response->body = $this->view->render();
    }

}
?>
