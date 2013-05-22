<?php
 
namespace App\Controller;
 
class Users extends \PHPixie\Controller {
 

/*
 *	дефолт
 */

    public function action_index() {
 
        $view = $this->pixie->view('main');
        $view->users = $this->pixie->orm->get('users')->find_all();
        $this->response->body = $view->render();
    }
	
	public function action_view() {
 
        //Show the single fairy page
        $view = $this->pixie->view('view');
 
        //$id = $this->request->param('id');
		$view->mainemail = $this->request->param('id');
        //$this->view->users = $this->pixie->orm->get('users', $id);
        $this->response->body = $view->render();
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
