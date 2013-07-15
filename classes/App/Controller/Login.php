<?php
/*

 */
namespace App\Controller;

class Login extends \App\Page {

   private $logmsg;


    public function action_view() {

       // $view = $this->pixie->view('main');
		$auth = $this->pixie->auth;

		if( ! $auth->user() )
			$this->view->subview 		= 'login_main';
		else
			$this->view->subview = 'login_view';



		$this->view->script_file	= '';
		$this->view->css_file 	= '<link rel="stylesheet" href="/login.css" type="text/css" />';

        $this->response->body	= $this->view->render();
    }

	public function action_login() {

        if($this->request->method == 'POST'){

            $login = $this->request->post('username');
            $password = $this->request->post('passwd');

            //Attempt to login the user using his
            //username and password
            $logged = $this->pixie->auth->provider('Password')->login($login, $password);

            //On successful login redirect the user to
            //our protected page
            if ( $logged)
		        return $this->redirect('/login/list');

        }

        return $this->redirect('/');
    }

    public function action_logout() {
        $this->pixie->auth->logout();
        $this->redirect('/');
    }

	public function action_list() {

		$auth = $this->pixie->auth;
echo $auth->has_role('admin1');
echo "--ss";
exit;
		if( ! $auth->user() )
			return $this->redirect('/login');

		$this->view->subview = 'login_view';
		$this->response->body = $this->view->render();
	}
}
?>
