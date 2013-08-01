<?php

namespace App\Controller;

class Roles extends \App\Page {

   private $role_id;

   protected function sanitize(&$value, $method) {

		$value =  trim($value) ;

		switch ( $method ) {
			case 'transport':
				if( !preg_match ('/\w+:\[(\d+\.)+\d+\]/', $value) ) {
					$this->logmsg .= "<span class='error'>Wrong entry for net in field $value</span>";
					return true;
				}
				break;
			case 'is_role':
				if ( ! preg_match('/(\w+\.)+(\w+)/',$value) ) {
					$this->logmsg .= "<span class='error'>Wrong entry for mail in field $value</span>";
					return true;
				}
				break;
			default:
				return false;
		}
	}

    public function action_view() {


		$this->view->subview 		= 'roles_main';

		$this->view->script_file	= '';//<script type="text/javascript" src="/roles.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/roles.css" type="text/css" />';


		$this->view->roles = $this->pixie->db
											->query('select')
											->table('roles')
											->execute()
											->as_array();

		$this->view->roles_block 	= $this->action_single();

        $this->response->body = $this->view->render();
    }


	public function action_single() {

		$view 		= $this->pixie->view('roles_view');
		$view->log 	= $this->getVar($this->logmsg,'');

		if( ! $this->request->get('name') )
			//return "<img class='lb' src=/roles.png />";
			return;

		$this->role_id = $this->getVar($this->role_id, $this->request->get('name'));

		$role = $this->pixie->db
								->query('select')->table('roles')
								->where('id', $this->role_id)
								->execute()
								->current();
		//Если ответ пустой
		if( ! count($role) )
			return "<strong>Домена с ID ".$this->role_id." не существует.</strong>";

		$view->role = $role;


		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
	}

	public function action_new() {

		$view 		= $this->pixie->view('roles_new');
		$view->log 	= $this->getVar($this->logmsg,'<strong>Ввод новой роли.</strong>');

		$pages = $this->pixie->db->query('select')->table('controllers')->execute();

		$view->pages = $this->getVar($pages,array());

		$this->response->body = $view->render();
	}

	public function action_add() {

        if ($this->request->method == 'POST') {

			$params = $this->request->post();

			$params['dom']  = $this->getVar($params['dom'], array());

			// Проверка на правильность заполнения (Новая запись)
			if( isset($params['role_name']) )
				$this->sanitize($params['role_name'], 'is_role' );

			// Проверка типа домена
			if( isset($params['delivery_to']) ) {
				$this->sanitize( $params['delivery_to'], 'net');
				$params['role_type'] = '2';
			}
			else {
				$params['delivery_to'] = 'virtual';
				$params['role_type'] = '0';
			}

			$data_insert = array(
								'role_name' 	=> $params['role_name'],
								'delivery_to' 	=> $params['delivery_to'],
								'role_type' 	=> $params['role_type']
								);
			$data_update = array(
								'role_notes'	=> $params['role_notes'],
								'all_enable'	=> $this->getVar($params['all_enable'],0),
								'all_email'		=> isset( $params['all_email'] ) ? $params['all_email'].'@'.$params['role_name'] : '',
								'active'		=> $this->getVar($params['active'],0)
								);

			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {

				// Если запись новая
				if( ! isset($params['role_id']) ) {

					$this->pixie->db->query('insert')->table('roles')
									->data(array_merge($data_insert,$data_update))
									->execute();

					$params['role_id'] = $this->pixie->db->insert_id();
				}
				// Если редактируем
				else {
					$this->pixie->db->query('update')->table('roles')
									->data($data_update)
									->where('role_id', $params['role_id'])
									->execute();
				}


				// Обработка алиасов
				foreach ($params['dom'] as $key=>$alias ) {

					$data_insert = array(
									'role_name' => $alias,
									'delivery_to' => $params['role_name'],
									);
					$data_update = array(
									'role_type' => '1',
									'active'	  => $params['stat'][$key]
									);

					if( $params['stat'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')->table('roles')
										->where('role_id',$params['fid'][$key])
										->execute();
					}
					elseif( $params['fid'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')->table('roles')
										->data(array_merge($data_insert, $data_update))
										->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('roles')
										->data($data_update)
										->where('role_id', $params['fid'][$key])
										->execute();
					}
				}
			}
			// Ошибки имели место - возвращаем форму
			if( isset( $this->logmsg ) ) {

				if ( isset($params['role_id']) ) {

					$this->role_id = $params['role_id'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['role_id'];

		}

	}


}
?>
