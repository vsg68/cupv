<?php
 
namespace App\Controller;
 
class Users extends \PHPixie\Controller {
 //~ 
	private $user_id;
	private $logmsg;

//	функция для тестирования строк на возможные значения
    private function sanitize($value,$key,$method) {

		$value = trim($value);
		
		switch ( $method ) {
			case 'empty':
				$value = isset($value) ? $value : '0';
				break;
			case 'notempty':
				if( $value == '' ) {
					 $this->logmsg .= "<span class='error'>Field $key can not be empty</span>";
				 }
				break;
			case 'net':
				if( !preg_match ('!((\d+\.)+\d+(/\d+)?,?\s*)+!', $value) ) {
					$this->logmsg .= "<span class='error'>Wrong entry for net in field $key</span>";
				}
				break;
			case 'is_number':
				if( is_number($value) ) {
					$this->logmsg .= "<span class='error'>Wrong entry for field $key</span>";
				}
				break;
			case 'is_mail':
				if ( ! preg_match('/(\w+)@(\w+\.)+(\w+)/',$value) ) {
					$this->logmsg .= "<span class='error'>Wrong entry for mail in field $key</span>";
				}
				break;
			default:
				
		}
	}	

    public function action_index() {

        $view = $this->pixie->view('main');
        $view->users = $this->pixie->db
							->query('select')->table('users')
							->execute();

        $this->response->body = $view->render();
    }
	
	public function action_new() {

		$view->log = isset($this->logmsg) ?  $this->logmsg : '';
		
        $view = $this->pixie->view('new');
        $view->domains = $this->pixie->db
								->query('select')
								->table('domains')
								->group_by('domain_name')
								->execute();
								
        $this->response->body = $view->render();
    }

	public function action_view() {

		$view = $this->pixie->view('view');
		// вывод лога
		$view->log = isset($this->logmsg) ?  $this->logmsg : '';

		$id = isset( $this->user_id ) ? $this->user_id : $this->request->param('id');
		
		$view->user = $this->pixie->db
								->query('select')->table('users')
								->where('user_id',$id)
								->execute()
								->current();

		$view->aliases = $this->pixie->db
								->query('select')->table('aliases')
								->where('alias_name',$view->user->mailbox)
								->where('or',array('delivery_to',$view->user->mailbox))
								->where('and',array('delivery_to','!=','alias_name'))
								->execute()
								->as_array();
		
        $this->response->body = $view->render();
    }
/*
 *	обработка запроса и вывод формы
 */
	public function action_add() {

        if ($this->request->method == 'POST') {

			$user = $this->request->post();
			unset($user['chk']);
			
			// обработка строк
			array_walk($user,array($this,'sanitize'),'notempty');		
			if( ! isset( $user['imap'] ) )  $user['imap'] = 0; 
			if( ! isset( $user['pop3'] ) )  $user['pop3'] = 0; 

			// Инициируем, чтоб не было ошибки при обработке несуществующего массива
			if( ! isset($user['alias']) ) $user['alias'] = array(); 
			if( ! isset($user['fwd']) )   $user['fwd']   = array();

			// Проверка на почтовый адрес
			array_walk( $user['alias'],array($this,'sanitize'),'is_mail' );
			array_walk( $user['fwd'],array($this,'sanitize'),'is_mail' );
			
			
			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg)) {
				//
				// Приходит обработка либо нового пользователя, либо изменение существующего
				//
				if ( ! $user['user_id'] && $user['login'] && $user['domain'] ) {
				// новый пользователь
					$this->pixie->db->query('insert')->table('users')
									->data(array(
										'username' 		=> $user['username'],
										'mailbox'		=> $user['login'].'@'.$user['domain'],
										'password' 		=> $user['password'],
										'md5password' 	=> md5($user['password']),
										'path'			=> $user['path'],
										'imap_enable' 	=> $user['imap'] + $user['pop3'],
										'allow_nets' 	=> $user['allow_nets'],
										'active'		=> 1
									))
									->execute();

					// для редиректа получаем id
					$user['user_id'] = $this->pixie->db->insert_id();								

				}
				elseif( $user['user_id'] && $user['mailbox'] ) {
				// Существующий пользователь
					$this->pixie->db->query('update')->table('users')
									->data(array(
										'username' 		=> $user['username'],
										'password' 		=> $user['password'],
										'md5password' 	=> md5($user['password']),
										'path'			=> $user['path'],
										'imap_enable' 	=> ( $user['imap'] + $user['pop3'] ),
										'allow_nets' 	=> $user['allow_nets'],
										'active'		=> $user['active']
									))
									->where('user_id',$user['user_id'])
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
//~ print_r($user);
//~ exit;
				// Возвращаемся обратно в форму редактирования
				$this->user_id = $user['user_id'];
				$this->logmsg = "<span class='success'>Изменено</span>";
				$this->action_view();
			}
			else {
			// Ошибки имели место
				if ( $user['user_id'] ) {

					$this->user_id = $user['user_id'];
					$this->action_view();
				}
				else 
					$this->action_new();
			}

		}
		
	}


}

?>
