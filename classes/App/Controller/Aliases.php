<?php
namespace App\Controller;

class Aliases extends \App\Page {


    public function action_view() {

		// Проверка легитимности пользователя и его прав
		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview 		= 'aliases';
		$this->view->script_file	= '<script type="text/javascript" src="/js/aliases.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/css/aliases.css" type="text/css" />';

		$this->view->entries = $this->pixie->db->query('select')
												->fields($this->pixie->db->expr('A.id,
																				ifnull(U1.username,"N/A") as from_username,
																				"->" as direction,
																				ifnull(U.username,"N/A") as to_username,
																				A.alias_name,
																				A.delivery_to,
																				A.alias_notes,
																				A.active'))
												->table('aliases','A')
												->join(array('users','U'),array('U.mailbox','A.delivery_to'))
												->join(array('users','U1'),array('U1.mailbox','A.alias_name'))
												->order_by('A.delivery_to')
												->execute();

        $this->response->body	= $this->view->render();
    }


	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$init		= $this->request->post('init');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;


        $view->data = $this->pixie->db->query('select')
										->table($tab)
										->where('id',$this->_id)
										->execute()
										->current();

		// Для дефолтных значений таблицы алиасов
		if( $init ) {
			$view->data = $this->pixie->db->query('select')
										->fields($this->pixie->db->expr('mailbox AS alias_name, mailbox AS delivery_to'))
										->table('users')
										->where('id',$init)
										->execute()
										->current();

		}

       $this->response->body = $view->render();
    }


	public function action_new() {

		$view 		= $this->pixie->view('aliases_new');
		$view->log 	= $this->getVar($this->logmsg,'');

		// Проверка на доступ
		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$this->response->body = $view->render();
	}

	public function action_add() {

        if ($this->request->method == 'POST') {

			// Проверка на доступ
			if( $this->permissions != $this::WRITE_LEVEL ) {
				$this->noperm();
				return false;
			}

			$params = $this->request->post();

			unset($params['chk']);

			// Инициируем, чтоб не было ошибки при обработке несуществующего массива
			$params['fname'] = $this->getVar($params['fname'], array());

			// Если ошибок все еще нет
			if( ! isset( $this->logmsg ) ) {
				// Обработка алиасов
				foreach ($params['fname'] as $key=>$fname ) {

					$entry = array(
									'alias_name' => $params['alias_name'],
									'delivery_to'=> $fname,
									'active'	 => $params['stat'][$key]
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
										->data($entry)
										->execute();

						// нам не важно какой будет id, главное, что он ведет к нужному адресу
						$params['alias_uid'] = $this->pixie->db->insert_id();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('aliases')
										->data($entry)
										->where('alias_id', $params['fid'][$key])
										->execute();
					}
				}
			}

			// Ошибки имели место - возвращаем форму
			if( isset( $this->logmsg ) ) {

				if ( isset($params['alias_uid']) ) {

					$this->_id = $params['alias_uid'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['alias_uid'];

		}

	}


}
?>
