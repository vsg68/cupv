<?php
 
namespace App\Controller;
 
class Users extends \App\Page {
 

/*
 *	дефолт
 */

    public function action_index() {
 
        //Include the list.php subtemplate
        $this->view->subview = 'list1';
 
        //Find all fairies and pass them to the view
        //ORM takes care of that
        $this->view->users = $this->pixie->orm->get('users')->find_all();
    }
	
/*
 *	вывод нужного шаблона
 */
	
	public function action_view() {
 
        //Show the single fairy page
        $this->view->subview = 'view';
 
        //Get the ID of the fairy from URL parameters
        $id = $this->request->param('id');
 
        //Find a fairy by ID and pass her to the view
        //ORM makes it very trivial too
        $this->view->users = $this->pixie->orm->get('users', $id);
    }
	
/*
 *	обработка запроса и вывод формы
 */
	public function action_add() {
 
        //If the HTTP method is 'POST'
        //it means that the form got submitted
        //and we should process it
        if ($this->request->method == 'POST') {
 
            //Create a new fairy
            $users = $this->pixie->orm->get('users');
 
			//~ if( isset($this->request->post('fio')) &&
				//~ isset($this->request->post('login')) &&
				//~ isset($this->request->post('passwd')) &&
				//~ $this->request->post('active') = 1
			//~ )
			//~ {
				//~ //Set her name from the form POST data
				//~ $users->login = $this->request->post('name');
 //~ 
				//~ //Set her interests from the form POST data
				//~ $users->passwd = $this->request->post('interests');
 //~ 
				//~ //Save her
				//~ $users->save();
 //~ 
				//~ //And redirect the user back to the list
				return $this->redirect('/');
        }
 
        //Show the form
        $this->view->subview = 'add';
    }
}

?>
