<?php

namespace App\Controller;

class BServer extends \App\Page {

    public function action_view() {


		$this->view->script_file = '';//'<script type="text/javascript" src="/dns.js"></script>';
		$this->view->css_file = '<link rel="stylesheet" href="/bserver.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$this->view->subview = 'bserver_main';

		$this->view->domains = $this->pixie->db->query('select','itbase')
												->table('names')
												->where('type','serv')
												->execute();

		$this->view->dns_block = $this->action_single();

        $this->response->body = $this->view->render();
    }

	public function action_new() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

        $view = $this->pixie->view('bserver_new');

		$view->log = isset($this->logmsg) ?  $this->logmsg : '';

        $this->response->body = $view->render();
    }

	public function action_del() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		if ($this->request->method != 'POST')
			return false;

        // удаляем зону

		 $params = $this->request->post();

		 $this->pixie->db->query('delete','itbase')
						 ->table('names')
						 ->where('id',$params['id'])
						 ->execute();

		 $this->pixie->db->query('delete','itbase')
						 ->table('records')
						 ->where('domain_id',$params['id'])
						 ->execute();
    }

	public function action_single() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$view = $this->pixie->view('bserver_view');

		// вывод лога
		$view->log = $this->getVar($this->logmsg,'');

		// если не редактирование,т.е. начальный вход
		if( ! $this->request->param('id') )
			return; // "<img class='lb' src='/Dns.png' />";

		$this->_id = $this->getVar($this->_id, $this->request->param('id'));

		$view->records = $this->pixie->db->query('select','itbase')
										->table('records')
										->where('names_id',$this->_id)
										->execute();


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

			if ( ! isset($params['domain_id']) ) {
				// новая зона
				$this->pixie->db->query('insert','dns')
								->table('domains')
								->data(array('name' => $params['zname'],
											 'master' => 'MASTER'))
								->execute();

				$params['domain_id'] = $this->pixie->db->insert_id('dns');

				//for($i=0; $i < count($params['fname']); $i++)
				foreach( $params['fname'] as $fname)
				{
					$params['stat'][] = 1;
					$params['fid'][] = 0;
				}
			}

			// Обработка записей
			foreach ($params['fname'] as $key=>$fname ) {

				$entry = array( 'name' 		=> $fname,
								'type' 		=> $params['ftype'][$key],
								'content' 	=> $params['faddr'][$key],
								'ttl'		=> $params['ttl'],
								'domain_id' => $params['domain_id']
						);
				// добавляем приоритет (для записи MX)
				if(  $params['ftype'][$key] == 'MX' ) {

					if( preg_match('/((?:\w+\.)+\w+)\s*\(\s*(\d+)\s*\)/', $params['faddr'][$key], $matches) ) {

						$entry['content'] = $matches[1];
						$entry['prio'] 	  =  $matches[2];
					}
					else
						$entry['prio'] 	  =  '10';
				}
//print_r($entry); continue;

				if( $params['stat'][$key] == 2 ) {
				// Удаление
					$this->pixie->db->query('delete','dns')
									->table('records')
									->where('id',$params['fid'][$key])
									->execute();
				}
				elseif( $params['fid'][$key] == 0 ) {  // or undefined
				// Новый
					$this->pixie->db->query('insert','dns')
									->table('records')
									->data($entry)
									->execute();
				}
				else {
				// Изменение
					$this->pixie->db->query('update','dns')
									->table('records')
									->data($entry)
									->where('id', $params['fid'][$key])
									->execute();
				}
			}
//exit;
			// Ошибки имели место
			if( isset( $this->logmsg ) ) {

				if ( $params['domain_id'] ) {
					// Ошибка во время редактирования
					$this->_id = $params['domain_id'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['domain_id'];
		}

	}


 }

?>
