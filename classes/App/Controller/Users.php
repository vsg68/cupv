<?php

namespace App\Controller;

class Users extends \App\Page {

    public function action_view() {


		$this->view->script_file = '<script type="text/javascript" src="../js/users.js"></script>';
		$this->view->css_file = '';//'<link rel="stylesheet" href="/users.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        //~ if( $this->permissions == $this::NONE_LEVEL ) {
			//~ $this->noperm();
			//~ return false;
		//~ }

		$this->view->subview = 'users_main';

		$this->view->users 	 = $this->pixie->db->query('select')
												->table('users')
												->execute();

//print_r($this->view->users); exit;
        $this->response->body = $this->view->render();
    }

	public function action_editform() {

		//~ if( $this->permissions == $this::NONE_LEVEL )
			//~ return $this->noperm();


		if( $this->request->method != 'POST' )
			return;

		$this->_id = $this->request->param('id');

		if( $this->request->post('t') == 'records' ) {
			$view = $this->pixie->view('form_aliases');
			$view->tab  = 'aliases';
		}
		else {
			$view = $this->pixie->view('form_users');
			$view->tab = 'users';
			$view->domains = $this->pixie->db->query('select')
										->table('domains')
										->group_by('domain_name')
										->where('delivery_to','virtual')
										->execute();
		}

        $view->data = $this->pixie->db->query('select')
										->table($view->tab)
										->where('id',$this->_id)
										->execute()
										->current();


        $this->response->body = $view->render();
    }

	public function action_records() {

		//~ if( $this->permissions == $this::NONE_LEVEL ) {
			//~ $this->noperm();
			//~ return false;
		//~ }

		// если не редактирование,т.е. начальный вход
		if( ! $this->request->param('id') )
			return; // "<img class='lb' src='/users.png' />";

		$this->_id = $this->request->param('id');

		$aliases = $this->pixie->db->query('select')
										->fields('id','alias_name', 'delivery_to', 'active')
										->table('aliases','A')
										->join(array('users','U1'),array('U1.mailbox','A.alias_name'))
										->join(array('users','U2'),array('U2.mailbox','A.delivery_to'))
										->where('U1.id',$this->_id)
										->where('or',array('U2.id',$this->_id))
										->execute();


		$data = array();

		foreach($aliases as $alias) {

			$data[] = array( $alias->alias_name,
							 $alias->delivery_to,
							 $alias->active,
							 'DT_RowId' => 'aliases-'.$alias->id,
							 'DT_RowClass' => ( $alias->active ) ? 'gradeA' : 'gradeU'
							 );
		}


		$this->response->body = ( $data ) ? json_encode($data) : "[[null,null,null]]" ;

    }

	public function action_edit() {

		//~ if( $this->permissions != $this::WRITE_LEVEL )
			//~ return $this->noperm();

        //~ if ($this->request->method != 'POST')
			//~ return;

		if( ! $params = $this->request->post() )
			return;

		if( $params['tab'] == 'users') {

			$entry = array( 'username' 		=> $params['username'],
							'mailbox'	 	=> $params['login'].'@'.$params['domain'],
							'password' 		=> $params['password'],
							'md5password' 	=> md5($params['password']),
							'path'			=> $this->getVar($params['path']),
							'imap_enable' 	=> $this->getVar($params['imap'],0),
							'allow_nets' 	=> $params['allow_nets'],
							'active'		=> $this->getVar($params['active'],0)
					);
		}
		else {

		}

		try {
			if ( ! isset($params['id']) ) {
				// новый пользователь
				$this->pixie->db->query('insert')
								->table( $params['tab'] )
								->data($entry)
								->execute();
				// return ?
				$params['id'] = $this->pixie->db->insert_id();
			}
			else {
			// Существующий пользователь
				$this->pixie->db->query('update')
								->table( $params['tab'] )
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}

			$this->response->body = $params['tab'].'-'.$params['id'];
		}
		catch( \Exception $e) {
				echo 'Something went wrong'.$e;
		}

	}


    public function action_searchdomain() {

		$test = $this->request->post('query');

		// Готовлю ответ в нужном формате
		$arr['suggestions'] = array();


		if(  preg_match('/^[^@]+@/', $test, $match_arr)) {

			$test = preg_replace('/^[^@]+@/','',$test);

			$domains = $this->pixie->db
								->query('select')
								->fields('domain_name')
								->table('domains')
								->where('domain_name', 'like', $test.'%')
								->where('and', array('delivery_to','virtual'))
								->execute();

			foreach($domains as $domain) {
			// заполняю массив данных доменами
				array_push( $arr['suggestions'], $match_arr[0].$domain->domain_name );
			}
		}

        $this->response->body = json_encode($arr);
    }
}

?>
