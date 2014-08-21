<?php
/*

 */
namespace App\Controller;

class Login extends \App\Page {

     public function action_view() {

		$this->view = $this->pixie->view('login');

		if( $this->auth->user() ) {  // если пусто - юзер не зарегистрировался

			$name = $this->auth->user()->login ;

			$pages = $this->pixie->db->query('select','admin')
											->fields($this->pixie->db->expr('S.name, S.note, COALESCE(C.class,"#") AS link'))
											->table('sections','S')
											->join(array('controllers','C'),array('S.id','C.section_id'),'LEFT')
											->join(array('rights','P'),array('C.id','P.control_id'),'LEFT')
											->join(array('roles','R'),array('R.id','P.role_id'),'LEFT')
											->join(array('slevels','SL'),array('SL.id','P.slevel_id'),'LEFT')
											->join(array('auth','A'),array('A.role_id','P.role_id'),'LEFT')
											->where('A.login',$name)
											->where('S.active',1)
											->where('R.active',1)
											->where('C.active',1)
											->where('A.active',1)
											->where('C.order',0)
											->where('SL.slevel','>',0)
                                            ->order_by('S.id')
											->group_by('S.name')
                                            ->limit(1)
											->execute()->as_array();

            // Если у нас есть доступ к какому-нить разделу - уходим на первый доступный
            if( $pages[0]->link ) {
                $this->redirect('/'.$pages[0]->link);
                return;
            }
		}

		$this->view->is_hidden = 0;

		$this->response->body = $this->view->render();

	}

	public function action_login() {

        if( $this->request->method != 'POST' )
			return false;

		$login 		= $this->request->post('username');
		$password 	= $this->request->post('password');

		$this->response->body = $this->auth->provider('password')->login($login, $password);

    }

    public function action_logout() {
        $this->auth->logout();
        $this->redirect('/');
    }


}
?>
