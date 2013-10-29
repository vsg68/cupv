<?php

namespace App\Controller;

class Users extends \App\Page {

    public function action_view() {


		$this->view->script_file = '<script type="text/javascript" src="../js/users.js"></script>';
		$this->view->css_file = '';//'<link rel="stylesheet" href="/users.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        //~ if( $this->permissions == $this::NONE_LEVEL )
			//~ return  $this->noperm();


		$this->view->subview = 'users_main';

		$this->view->users 	 = $this->pixie->db->query('select')
												->table('users')
												->execute();

        $this->response->body = $this->view->render();
    }

	public function action_showEditForm() {

		//~ if( $this->permissions == $this::NONE_LEVEL )
			//~ return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$init		= $this->request->post('init');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;

		if( $tab == 'users' ) {
			$view->domains = $this->pixie->db->query('select')
										->table('domains')
										->group_by('domain_name')
										->where('delivery_to','virtual')
										->execute();
		}

        $view->data = $this->pixie->db->query('select')
										->table($tab)
										->where('id',$this->_id)
										->execute()
										->current();

		// Для дефолтных значений таблицы алиасов
		if( $init ) {
			$view->data = $this->pixie->db->query('select')
										->fields($this->pixie->db->expr('mailbox AS alias_name, mailbox AS delivery_to, null AS alias_notes'))
										->table('users')
										->where('id',$init)
										->execute()
										->current();

		}

        $this->response->body = $view->render();
    }

	public function action_delEntry() {

		//~ if( $this->permissions == $this::NONE_LEVEL )
			//~ return $this->noperm();


		if( ! $mbox = $this->request->post('mbox') )
			return;

		$this->pixie->db->query('delete')
						->table('users')
						->where('mailbox',$mbox)
						->execute();

        $this->pixie->db->query('delete')
						->table('aliases')
						->where('delivery_to',$mbox)
						->execute();
    }

	public function action_records() {

		//~ if( $this->permissions == $this::NONE_LEVEL )
			//~ return $this->noperm();

		// если не редактирование,т.е. начальный вход
		if( ! $this->_id = $this->request->param('id'))
			return;

		$data = array();

		$aliases = $this->pixie->db->query('select')
										->fields('id','alias_name', 'delivery_to', 'alias_notes', 'active')
										->table('aliases','A')
										->join(array('users','U1'),array('U1.mailbox','A.alias_name'))
										->join(array('users','U2'),array('U2.mailbox','A.delivery_to'))
										->where('U1.id',$this->_id)
										->where('or',array('U2.id',$this->_id))
										->execute();

		foreach($aliases as $alias)
			$data[] = array( $alias->alias_name,
							 $alias->delivery_to,
							 $alias->alias_notes,
							 $alias->active,
							 'DT_RowId' => 'aliases-'.$alias->id,
							 'DT_RowClass' => 'gradeA'
							);
		$this->response->body = ( $data ) ? json_encode($data) : "[[null,null,null,null]]" ;

    }

	public function action_edit() {

		//~ if( $this->permissions != $this::WRITE_LEVEL )
			//~ return $this->noperm();


		if( ! $params = $this->request->post() )
			return;
		$returnData  = array();

		if( $params['tab'] == 'users')
			// Массив, который будем возвращать
			$entry = array( 'username' 		=> $params['username'],
							'mailbox'	 	=> $params['login'].'@'.$params['domain'],
							'password' 		=> $params['password'],
							'md5password' 	=> md5($params['password']),
							'allow_nets' 	=> $this->getVar($params['allow_nets'],'192.168.0.0/24'),
							'path'			=> $this->getVar($params['path']),
							'acl_groups' 	=> $this->getVar($params['acl_groups']),
							'imap_enable' 	=> $this->getVar($params['imap'],0),
							'active'		=> $this->getVar($params['active'],0)
							);
		else
			$entry = array('alias_name' => $this->getVar($params['alias_name']),
						   'delivery_to'=> $this->getVar($params['delivery_to']),
						   'alias_notes'=> $this->getVar($params['alias_notes']),
						   'active'		=> $this->getVar($params['active'],0)
						 );


		try {
			if ( $params['id'] == 0 ) {
				// новый пользователь
				$vars = $this->pixie->db->query('insert')
								->table( $params['tab'] )
								->data($entry)
								->execute();

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



			// для правильного отображения строки в таблице
			if( $params['tab'] == 'users')
				$entry['md5password'] = $params['domain'];

			// Массив, который будем возвращать
			$returnData = array_values($entry);
			$returnData['DT_RowId']	= $params['tab'].'-'.$params['id'];
		}
		catch( \Exception $e) {
				return 'Something went wrong'.$e;
		}

		$this->response->body = json_encode($returnData);
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
