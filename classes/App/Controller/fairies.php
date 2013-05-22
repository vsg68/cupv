<?php
 
namespace App\Controller;
 
class Fairies extends \App\Page {
 


    public function action_index() {
 
        //Include the list.php subtemplate
        $this->view->subview = 'list';
 
        //Find all fairies and pass them to the view
        //ORM takes care of that
        $this->view->fairies = $this->pixie->orm->get('fairy')->find_all();
    }
	
	
	public function action_view() {
 
        //Show the single fairy page
        $this->view->subview = 'view';
 
        //Get the ID of the fairy from URL parameters
        $id = $this->request->param('id');
 
        //Find a fairy by ID and pass her to the view
        //ORM makes it very trivial too
        $this->view->fairy = $this->pixie->orm->get('fairy', $id);
    }
	
	public function action_add() {
 
        //If the HTTP method is 'POST'
        //it means that the form got submitted
        //and we should process it
        if ($this->request->method == 'POST') {
 
            //Create a new fairy
            $fairy = $this->pixie-> orm->get('fairy');
 
            //Set her name from the form POST data
            $fairy->name = $this->request->post('name');
 
            //Set her interests from the form POST data
            $fairy->interests = $this->request->post('interests');
 
            //Save her
            $fairy->save();
 
            //And redirect the user back to the list
            return $this->redirect('/');
        }
 
        //Show the form
        $this->view->subview = 'add';
    }
}