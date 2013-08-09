<?php

namespace App\Controller;

class Users extends \App\Page {

    public function action_view() {


		$this->view->script_file = '<script type="text/javascript" src="/users.js"></script>';
		$this->view->css_file = '<link rel="stylesheet" href="/users.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$this->view->subview = 'users_main';

		$this->view->users 	 = $this->pixie->db->query('select')
												->table('users')
												->order_by('mailbox')
												->execute();

		$this->view->domains = $this->pixie->db->query('select')
												->fields('domain_name')
												->table('domains')
												->where('delivery_to','virtual')
												->execute();

		$this->view->users_block = $this->action_single();

        $this->response->body = $this->view->render();
    }

	public function action_new() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

        $view = $this->pixie->view('users_new');

		$view->log = isset($this->logmsg) ?  $this->logmsg : '';

        $view->domains = $this->pixie->db->query('select')
										->table('domains')
										->group_by('domain_name')
										->where('delivery_to','virtual')
										->execute();

        $this->response->body = $view->render();
    }

	public function action_single() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$view = $this->pixie->view('users_view');

		// вывод лога
		$view->log = $this->getVar($this->logmsg,'');

		// если не редактирование,т.е. начальный вход
		if( ! $this->request->param('id') )
			return; // "<img class='lb' src='/users.png' />";

		$this->_id = $this->getVar($this->_id, $this->request->param('id'));

		$view->user = $this->pixie->db->query('select')
										->table('users')
										->where('user_id',$this->_id)
										->execute()
										->current();

		$view->aliases = $this->pixie->db->query('select')
										->fields('alias_id','alias_name', 'delivery_to', 'active')
										->table('aliases','A')
										->join(array('users','U1'),array('U1.mailbox','A.alias_name'))
										->join(array('users','U2'),array('U2.mailbox','A.delivery_to'))
										->where('U1.user_id',$this->_id)
										->where('or',array('U2.user_id',$this->_id))
										->execute()
										->as_array();

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

        $this->response->body = $view->render();
    }

	public function action_add() {

		if( $this->permissions != $this::WRITE_LEVEL ) {
			$this->noperm();
			return false;
		}

        if ($this->request->method == 'POST') {

			$params = $this->request->post();
			unset($params['chk']);

			// Инициируем, чтоб не было ошибки при обработке несуществующего массива
			$params['fname']	= $this->getVar($params['fname'],array());

			$entry = array('username' 		=> $params['username'],
							'password' 		=> $params['password'],
							'md5password' 	=> md5($params['password']),
							'path'			=> $this->getVar($params['path']),
							'imap_enable' 	=> $this->getVar($params['imap'],0),
							'allow_nets' 	=> $params['allow_nets'],
							'active'		=> $this->getVar($params['active'],0)
					);
			// для нового пользователя добавляем mailbox
			if(! isset($params['user_id']))
				$entry['mailbox'] = $params['mailbox'] = $params['login'].'@'.$params['domain'];

			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {

				if ( ! isset($params['user_id']) ) {
					// новый пользователь
					$this->pixie->db->query('insert')
									->table('users')
									->data($entry)
									->execute();

					$params['user_id'] = $this->pixie->db->insert_id();
				}
				else {
				// Существующий пользователь
					$this->pixie->db->query('update')
									->table('users')
									->data($entry)
									->where('user_id',$params['user_id'])
									->execute();
				}

				// Обработка алиасов  и форварда
				foreach ($params['fname'] as $key=>$fname ) {

					/*	 0 - alias; 1 - forward
					 *	 alias_name => fname
					 *   delivery_to => mailbox
					 *   и наоборот для форварда
					 */
					$dataArr = array('alias_name' 	=> ( $params['ftype'][$key] ) ? $params['mailbox'] : $fname,
									 'delivery_to'	=> ( $params['ftype'][$key] ) ? $fname : $params['mailbox'],
									 'active'		=> $params['stat'][$key]
							        );

					if( $params['stat'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')
										->table('aliases')
										->where('alias_id',$params['fid'][$key])
										->execute();
					}
					elseif( $params['fid'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')
										->table('aliases')
										->data($dataArr)
										->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')
										->table('aliases')
										->data($dataArr)
										->where('alias_id', $params['fid'][$key])
										->execute();
					}
				}
			}
			// Ошибки имели место
			if( isset( $this->logmsg ) ) {

				if ( $params['user_id'] ) {
					// Ошибка во время редактирования
					$this->_id = $params['user_id'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['user_id'];
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
