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

		$this->view->script_file	= '<script type="text/javascript" src="/roles.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/roles.css" type="text/css" />';


		$this->view->roles = $this->pixie->db->query('select')
											->table('roles')
											->execute();

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

		$view->role = $this->pixie->db->query('select')->table('roles')
								->where('id', $this->role_id)
								->execute()
								->current();

		$view->pages = $this->pixie->db->query('select')
								->table('page_roles','P')
								->join(array('controllers','C'),array('C.id','P.control_id'),'LEFT')
								->where('role_id', $this->role_id)
								->execute();

		$view->slevels = $this->pixie->db->query('select')->table('slevels')->execute()->as_array();

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
	}

	public function action_new() {

		$view 		= $this->pixie->view('roles_new');
		$view->log 	= $this->getVar($this->logmsg,'<strong>Ввод новой роли.</strong>');

		$pages = $this->pixie->db->query('select')
									->fields($this->pixie->db->expr('C.id AS ctrl_id, C.name AS ctrl_name, S.name AS sect_name'))
									->table('controllers','C')
									->join(array('sections','S'),array('S.id','C.section_id'),'LEFT')
									//->group_by('section_id')
									->order_by('section_id')
									->order_by('arrange')
									->execute();
		$view->pages = $this->getVar($pages,array());

		// Доступ
		$view->slevels = $this->pixie->db->query('select')->table('slevels')->execute()->as_array();

		$this->response->body = $view->render();
	}

	public function action_add() {

        if ($this->request->method == 'POST') {

			$params = $this->request->post();

			$role_entry = array(
								'name' 	=> $params['role_name'],
								'notes'	=> $this->getVar($params['role_notes'],''),
								'active'		=> $this->getVar($params['active'],0)
								);

			// Это новая запись?
			$is_update = $this->getVar($params['role_id'], 0);

			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {

				// Если запись новая
				if( ! isset($params['role_id']) ) {

					$this->pixie->db->query('insert')->table('roles')
									->data($role_entry)
									->execute();

					$params['role_id'] = $this->pixie->db->insert_id();
				}
				// Если редактируем
				else {
					$this->pixie->db->query('update')->table('roles')
									->data($role_entry)
									->where('id', $params['role_id'])
									->execute();
				}


				// Обработка ролей
				foreach ($params['page'] as $key=>$page_id ) {

					$roleInPage = array(
									'role_id'	 => $params['role_id'],
									'control_id' => $page_id,
									'slevel_id'	 => $params['p-'.$page_id]
									);

					if( !$is_update ) {
					// Новый
						$this->pixie->db->query('insert')->table('page_roles')
										->data($roleInPage)
										->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('page_roles')
										->data($roleInPage)
										->where('role_id', $params['role_id'])
										->where('control_id', $page_id)
										->execute();
					}
				}
			}
			// Ошибки имели место - возвращаем форму
			if( isset( $this->logmsg ) ) {

				if ( $is_update ) {

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
