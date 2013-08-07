<?php
/*

 */
namespace App\Controller;

class Auth extends \App\Page {

	private $auth_id;
	private $pages;

    public function action_view() {

 		$this->view->script_file	= '<script type="text/javascript" src="/auth.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/auth.css" type="text/css" />';

		$this->view->subview = 'auth_main';

		$this->view->users = $this->pixie->db
											->query('select')
											->fields($this->pixie->db->expr('R.name AS role, A.*'))
											->table('auth','A')
											->join(array('roles','R'),array('A.role_id','R.id'))
											->execute();


		$this->view->auth_block = $this->action_single();

        $this->response->body	= $this->view->render();
    }

	public function action_single() {

		$view 		= $this->pixie->view('auth_view');
		$view->log 	= $this->getVar($this->logmsg,'');

		if( ! $this->request->get('name') )
			//return "<img class='lb' src=/domains.png />";
			return;

		$this->auth_id = $this->getVar($this->auth_id, $this->request->get('name'));

		$view->auth = $this->pixie->db->query('select')->table('auth')
										->where('id', $this->auth_id)
										->execute()
										->current();

		$view->roles = $this->pixie->db->query('select')
										->table('roles')
										->execute()
										->as_array();

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
    }

	public function action_new() {

		$view 	   = $this->pixie->view('auth_new');
		$view->log = $this->getVar($this->logmsg,'<strong>Создание нового пользователя.</strong>');

		$view->roles = $this->pixie->db
										->query('select')
										->table('roles')
										->execute();
		$view->auth = '';

		$this->response->body = $view->render();
	}

    public function action_add() {

       if ($this->request->method == 'POST') {

			$params = $this->request->post();
			unset($params['chk']);

			// обработка строк
//			array_walk($params,array($this,'sanitize'),'notempty');

			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {
				//
				// Приходит обработка либо новой записи, либо изменение существующего
				//
				$entry = array( 'login'	  => $params['auth_login'],
								'note'	  => $params['auth_note'],
								'role_id' => $params['role_id'],
								'active'  => $this->getVar( $params['active'],0 )
								);
				if( isset( $params['auth_passwd']) )
								$entry['passwd']  = $this->auth->provider('Password')->hash_password($params['auth_passwd']); // хешируем пароль средством модля


				if ( ! isset($params['auth_id']) ) {
				// новая запись
					$this->pixie->db->query('insert')->table('auth')
									->data($entry)
									->execute();

					$params['auth_id'] = $this->pixie->db->insert_id();
				}
				else {
				// Запись существует
					$this->pixie->db->query('update')->table('auth')
									->data($entry)
									->where('id',$params['auth_id'])
									->execute();
				}
			}

			// Ошибки имели место
			if( isset( $this->logmsg ) ) {

				if ( $params['auth_id'] ) {
					// Ошибка во время редактирования
					$this->role_id = $params['auth_id'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['auth_id'];
		}

	}

}
?>
