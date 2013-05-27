<?php
 
namespace App\Controller;
 
class Users extends \PHPixie\Controller {
 //~ 
    public function action_index() {
 
        $view = $this->pixie->view('main');
        $view->users = $this->pixie->db
							->query('select')->table('users')
							->execute();

        $this->response->body = $view->render();
    }
	
	public function action_new() {

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

		$view->user = $this->pixie->db
								->query('select')->table('users')
								->where('user_id',$this->request->param('id'))
								->execute()
								->current();

		$view->aliases = $this->pixie->db
								->query('select')->table('aliases')
								->where('alias_name',$view->user->mailbox)
								->where('or',array('delivery_to',$view->user->mailbox))
								->execute()
								->as_array();
		
        $this->response->body = $view->render();
    }
/*
 *	обработка запроса и вывод формы
 */
	public function action_add1() {
 
        //If the HTTP method is 'POST'
        //it means that the form got submitted
        //and we should process it
        if ($this->request->method == 'POST') {

			//~ $user = array(
					//~ 'username' 		=> $this->request->post('fio'),
					//~ 'password' 		=> $this->request->post('passwd'),
					//~ 'md5password'	=> md5($this->request->post('passwd')),
					//~ 'path'			=> $this->request->post('path'),
					//~ 'imap_enable'	=> $this->request->post('imap') + $this->request->post('pop3'),
					//~ 'allow_nets'	=> $this->request->post('nets')
					//~ );
			$user = json_decode($this->request->post(),true);

			$id = $user->id;
			
			if ( ! $id && $this->request->post('login') && $this->request->post('domain') ) {

				$user['mailbox'] = $this->request->post('login').'@'.$this->request->post('domain');

				$result = $this->pixie->db
								->query('insert')->table('users')
								->data($user)
								->execute();

				// для редиректа получаем id
				$id = $this->pixie->db->insert_id();								

			} elseif( $id && $this->request->post('mailbox') ) {

				$user['active'] = $this->request->post('active');

				$result = $this->pixie->db
								->query('update')->table('users')
								->data($user)
								->where('user_id',$id)
								->execute();
			}

			//return $this->redirect('/users/view/'.$id);
		}
	}	
//~ 
	public function action_add() {

        if ($this->request->method == 'POST') {

			$user = $this->request->post();

			if ( ! $user['user_id'] && $user['login'] && $user['domain'] ) {
			// новый пользователь
				$result = $this->pixie->db
								->query('insert')->table('users')
								->data(array(
									'username' 		=> $user['username'],
									'mailbox'		=> $user['login'].'@'.$user['domain'],
									'password' 		=> $user['password'],
									'md5password' 	=> md5($user['password']),
									'path'			=> $user('path'),
									'imap_enable' 	=> $user('imap') + $user('pop3'),
									'allow_nets' 	=> $user('allow_nets'),
									'active'		=> 1
								))
								->execute();

				// для редиректа получаем id
				$user['user_id'] = $this->pixie->db->insert_id();								

			} elseif( $user['user_id'] && $user['mailbox'] ) {

				$result = $this->pixie->db
								->query('update')->table('users')
								->data(array(
									'username' 		=> $user['username'],
									'password' 		=> $user['password'],
									'md5password' 	=> md5($user['password']),
									'path'			=> $user['path'],
									//'imap_enable' 	=> ( $user['imap'] + $user['pop3'] ),
									'imap_enable' 	=> ( '' + 1 ),
									'allow_nets' 	=> $user['allow_nets'],
									'active'		=> $user['active']
								))
								->where('user_id',$user['user_id'])
								->execute();
			}

			if(1) {
				// Если прошло изменение - выводим пользователя
				$view = $this->pixie->view('view');
				$view->user = $this->pixie->db
								->query('select')->table('users')
								->where('user_id',$user['user_id'])
								->execute()
								->current();
print_r($view->user);
exit;
				$view->aliases = array();
				$this->response->body = $view->render();
			} else {
				
			}	
			//return $this->redirect('/users/view/'.$user['user_id']);
		}
		
	}
/*
    public function sanitize($methods,$datastr) {

		foreach( $methods as  $method) {
			switch ( $method ) {
				case 'empty':
					$datastr = trim($datastr);
					break;
				case 'net':
				case 'is_number':
				case 'is_mail':
					if ( ! preg_match('/(\w+)@(\w+\.)+(\w+)/',$datastr) ) {
						$log
					break;
				
		
	}	
*/
}

?>
