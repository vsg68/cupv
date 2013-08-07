<?php
/*

 */
namespace App\Controller;

class Login extends \App\Page {

     public function action_view() {

 		$this->view->script_file	= '<script type="text/javascript" src="/login.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/login.css" type="text/css" />';

		if( $this->auth->user() ) {  // если пусто - юзер не зарегистрировался

			$name = $this->auth->user()->login ;

			$this->view->pages = $this->pixie->db->query('select')
											->fields($this->pixie->db->expr('DISTINCT S.*, COALESCE(LN.class,"#") AS link'))
											->table('sections','S')
											->join(array('slevels','SL'),array('SL.id','S.slevel_id'),'LEFT')
											->join(array('controllers','C'),array('S.id','C.section_id'),'LEFT')
											->join(array('page_roles','R'),array('C.id','R.control_id'),'LEFT')
											->join(array('slevels','CL'),array('CL.id','R.slevel_id'),'LEFT')
											->join(array('auth','A'),array('A.role_id','R.role_id'),'LEFT')
											->join(array('controllers','LN'),array('S.id','LN.section_id'),'LEFT')
											->where('A.login',$name)
											->where('S.active',1)
											->where('LN.arrange',0)
											->where($this->pixie->db->expr('IFNULL(CL.slevel,0) >= SL.slevel'),1)
											->where('or',array('SL.slevel',0))
											->execute();

			$this->view->subview = 'login_view';
		}
		else {
			$this->view->subview = 'login_main';


		}

        $this->response->body = $this->view->render();
    }

	public function action_login() {

        if($this->request->method == 'POST'){

            $login 		= $this->request->post('username');
            $password 	= $this->request->post('passwd');

            $this->auth->provider('Password')->login($login, $password);
        }
        return $this->redirect('/');
    }

    public function action_logout() {
        $this->auth->logout();
        $this->redirect('/');
    }


}
?>
