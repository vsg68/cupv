<?php

namespace App\Controller;

class Users extends \App\Page {
 //~
	private $mailbox;

//	функция для тестирования строк на возможные значения


    public function action_view() {


		$this->view->script_file = '<script type="text/javascript" src="/users.js"></script>';
		$this->view->css_file = '<link rel="stylesheet" href="/users.css" type="text/css" />';

		//~ // Проверка легитимности пользователя и его прав
        //~ if( ! $this->check_permissions() )
			//~ return false;


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

		if( ! $this->is_approve() ) {
			$this->response->body = $this::RIGHTS_ERROR;
			return;
		}

        $view = $this->pixie->view('users_new');

		$view->log = isset($this->logmsg) ?  $this->logmsg : '';

        $view->domains = $this->pixie->db
								->query('select')
								->table('domains')
								->group_by('domain_name')
								->where('delivery_to','virtual')
								->execute();

        $this->response->body = $view->render();
    }

	public function action_single() {

		if( ! $this->is_approve() ) {
			$this->response->body = $this::RIGHTS_ERROR;
			return;
		}

		$view = $this->pixie->view('users_view');

		// вывод лога
		$view->log = isset($this->logmsg) ?  $this->logmsg : '';

		if( ! $this->request->get('name') )
			return; // "<img class='lb' src='/users.png' />";

		$mailbox = isset( $this->mailbox ) ? $this->mailbox : $this->request->get('name');

		$view->user = $this->pixie->db
								->query('select')->table('users')
								->where('mailbox',$mailbox)
								->execute()
								->current();

		$view->aliases = $this->pixie->db
								->query('select')->table('aliases')
								->where('alias_name',$mailbox)
								->where('or',array('delivery_to',$mailbox))
								->where('and',array('delivery_to','!=','alias_name'))
								->execute()
								->as_array();


		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

        $this->response->body = $view->render();
    }

	public function action_add() {

        if ($this->request->method == 'POST') {

			$user = $this->request->post();
			unset($user['chk']);

			// обработка строк
			array_walk($user,array($this,'sanitize'),'notempty');
			if( ! isset( $user['imap'] ) )  $user['imap'] = 0;
			if( ! isset( $user['active'] ) )  $user['active'] = 0;
			if( ! isset( $user['path']) || $user['path'] == '' )  $user['path'] = null;

			// Инициируем, чтоб не было ошибки при обработке несуществующего массива
			if( ! isset($user['alias']) ) $user['alias'] = array();
			if( ! isset($user['fwd']) )   $user['fwd']   = array();

			// Проверка на почтовый адрес
			array_walk( $user['alias'],array($this,'sanitize'),'is_mail' );
			array_walk( $user['fwd'],array($this,'sanitize'),'is_mail' );


			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {
				//
				// Приходит обработка либо нового пользователя, либо изменение существующего
				//
				if ( ! isset($user['mailbox']) ) {
					// новый пользователь

					$user['mailbox'] = $user['login'].'@'.$user['domain'];

					$this->pixie->db->query('insert')->table('users')
									->data(array(
										'username' 		=> $user['username'],
										'mailbox'		=> $user['mailbox'],
										'password' 		=> $user['password'],
										'md5password' 	=> md5($user['password']),
										'path'			=> $user['path'],
										'imap_enable' 	=> $user['imap'],
										'allow_nets' 	=> $user['allow_nets'],
										'active'		=> 1
									))
									->execute();

				}
				else {
				// Существующий пользователь
					$this->pixie->db->query('update')->table('users')
									->data(array(
										'username' 		=> $user['username'],
										'password' 		=> $user['password'],
										'md5password' 	=> md5($user['password']),
										'path'			=> $user['path'],
										'imap_enable' 	=> $user['imap'],
										'allow_nets' 	=> $user['allow_nets'],
										'active'		=> $user['active']
									))
									->where('mailbox',$user['mailbox'])
									->execute();
				}

				// Обработка алиасов
				foreach ($user['alias'] as $key=>$alias ) {

					if( $user['alias_st'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')->table('aliases')
										->where('alias_id',$user['alias_id'][$key])
										->execute();
					}
					elseif( $user['alias_id'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')->table('aliases')
										->data(array(
											'alias_name' => $alias,
											'delivery_to'=> $user['mailbox'],
											'active'	 => $user['alias_st'][$key]
										))->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('aliases')
										->data(array(
											'alias_name' => $alias,
											'delivery_to'=> $user['mailbox'],
											'active'	 => $user['alias_st'][$key]
										))
										->where('alias_id', $user['alias_id'][$key])
										->execute();
					}
				}

				// Обработка форварда
				foreach ($user['fwd'] as $key=>$fwd ) {

					if( $user['fwd_st'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')->table('aliases')
										->where('alias_id',$user['fwd_id'][$key])
										->execute();
					}
					elseif( $user['fwd_id'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')->table('aliases')
										->data(array(
											'alias_name' => $user['mailbox'],
											'delivery_to'=> $fwd,
											'active'	 => $user['fwd_st'][$key]
										))->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('aliases')
										->data(array(
											'alias_name' => $user['mailbox'],
											'delivery_to'=> $fwd,
											'active'	 => $user['fwd_st'][$key]
										))
										->where('alias_id', $user['fwd_id'][$key])
										->execute();
					}
				}

			}

			// Ошибки имели место
			if( isset( $this->logmsg ) ) {

				if ( $user['user_id'] ) {
					// Ошибка во время редактирования
					$this->mailbox = $user['mailbox'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $user['mailbox'];
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
