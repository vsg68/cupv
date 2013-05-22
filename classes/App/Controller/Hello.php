<?php
namespace App\Controller;

class Hello extends \App\Page {

    //'index' is the default action
    public function action_index() {
 
        //The value of $this->response->body
        //Will be echoed to the user
       //// $this->response->body="This is the listing page";
	    $view = $this->pixie->view('main');
 
		//Pass a variable to the view
		$view->message = 'hello';
 
		//Render the view and display it
		$this->response->body = $view->render();
    }
 
    public function action_view() {
        $this->response->body="You can view a single fairy on this page";
    }
 
    public function action_add() {
        $this->response->body="Here will be a form for adding fairies";
    }
 

}