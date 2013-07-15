<?php
/*

 */
namespace App\Controller;

class Login extends \App\Page {

     public function action_view() {

 		$this->view->script_file	= '';
		$this->view->css_file 		= '<link rel="stylesheet" href="/login.css" type="text/css" />';


		if( $this->request->param('id') == '403' )
			$this->view->subview = 'login_403';
		elseif( $this->is_logged() ) {
			$this->view->role = $this->user_role;
			$this->view->subview = 'login_view';
		}
		else
			$this->view->subview = 'login_main';


        $this->response->body	= $this->view->render();
    }

	public function action_login() {

        if($this->request->method == 'POST'){

            $login 		= $this->request->post('username');
            $password 	= $this->request->post('passwd');

            $logged 	= $this->pixie->auth->provider('Password')->login($login, $password);
        }

        return $this->redirect('/');
    }

    public function action_logout() {
        $this->pixie->auth->logout();
        $this->redirect('/');
    }


}
?>
