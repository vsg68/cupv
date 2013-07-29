<?php
/*

 */
namespace App\Controller;

class Login extends \App\Page {

     public function action_view() {

 		$this->view->script_file	= '';
		$this->view->css_file 		= '<link rel="stylesheet" href="/login.css" type="text/css" />';


		if( $name = $this->pixie->auth->user()->login ) {

			//~ $roles = $this->pixie->db->query('select')
											//~ ->table('roles','X')
											//~ ->join(array('auth','Y'),array('Y.id','X.auth_id'),'LEFT')
											//~ ->where('Y.login',$name)
											//~ ->execute();
//~
			//~ $this->view->role = $roles;
			$this->view->subview = 'login_view';
		}
		else {
			$this->view->subview = 'login_main';
			$this->pages = $this->pixie->db->query('select')
											->table('sections')
											->where()
		}

        $this->response->body	= $this->view->render();
    }

	public function action_login() {

        if($this->request->method == 'POST'){

            $login 		= $this->request->post('username');
            $password 	= $this->request->post('passwd');

            $logged 	= $this->pixie->auth->provider('Password')->login($login, $password);
            //~ if( $this->is_logged() )
				//~ setcookie('SECURITY_LEVEL',$this->user_level);

        }

        return $this->redirect('/');
    }

    public function action_logout() {
        $this->pixie->auth->logout();
        $this->redirect('/');
    }


}
?>
