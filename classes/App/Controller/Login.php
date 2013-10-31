<?php
/*

 */
namespace App\Controller;

class Login extends \App\Page {

     public function action_view() {

		$this->view = $this->pixie->view('login');

		$this->view->script_file	= '<script type="text/javascript" src="/js/login.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/css/login.css" type="text/css" />';

		if( $this->auth->user() ) {  // если пусто - юзер не зарегистрировался

			$name = $this->auth->user()->login ;

			$this->view->pages = $this->pixie->db->query('select')
											->fields($this->pixie->db->expr('S.name, S.note, COALESCE(C.class,"#") AS link'))
											->table('sections','S')
											->join(array('controllers','C'),array('S.id','C.section_id'),'LEFT')
											->join(array('page_roles','P'),array('C.id','P.control_id'),'LEFT')
											->join(array('roles','R'),array('R.id','P.role_id'),'LEFT')
											->join(array('slevels','SL'),array('SL.id','P.slevel_id'),'LEFT')
											->join(array('auth','A'),array('A.role_id','P.role_id'),'LEFT')
											->where('A.login',$name)
											->where('S.active',1)
											->where('R.active',1)
											->where('C.active',1)
											->where('A.active',1)
											->where('C.arrange',0)
											->where('SL.slevel','>',0)
											->group_by('S.name')
											->execute();

		}


		$this->view->is_hidden = ( isset($name) ? 0: 1 );

		$this->response->body = $this->view->render();

	}

	public function action_login() {

        if($this->request->method == 'POST'){

            $login 		= $this->request->post('username');
            $password 	= $this->request->post('passwd');
			try{
				$this->auth->provider('password')->login($login, $password);

				$name = $this->auth->user()->login;
				print_r('xx'.$name); exit;
				$this->auth->logout();
				return 'name is: '.$name;

			}
			catch( \Exception $e ){
				return "something going wrong";
			}

			return '';
        }

    }

    public function action_logout() {
        $this->auth->logout();
        $this->redirect('/');
    }


}
?>
