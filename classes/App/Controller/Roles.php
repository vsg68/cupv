<?php

namespace App\Controller;

class Roles extends \App\Page {

    public function action_view() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview 		= 'roles';

		$this->view->script_file	= '<script type="text/javascript" src="/js/roles.js"></script>';
		$this->view->css_file 		= '';//<link rel="stylesheet" href="/roles.css" type="text/css" />';

		$roles = array();

		$this->view->entries = $this->pixie->db->query('select')
											->table('roles')
											->execute();

        $this->response->body = $this->view->render();
    }

/*
 * Функция показывает общую матрицу
 */
	public function action_showTable() {
return;

		$matrix = $this->pixie->db->query('select')
								->fields(array('C.name','ctrl_name'),
										 array('R.name','role_name'),
										 array($this->pixie->db->expr('LEFT(IFNULL(L.name,"N"),1)'),'slevel')
										 )
								->table('page_roles','P')
								->join(array('controllers','C'),array('C.id','P.control_id'),'LEFT')
								->join(array('slevels','L'),array('L.id','P.slevel_id'),'LEFT')
								->join(array('roles','R'),array('R.id','P.role_id'),'LEFT')
								->where($this->pixie->db->expr('R.name IS NOT NULL'),1)
								->order_by('C.id')
								->order_by('R.name')
								->execute()
								->as_array();


		foreach($matrix as $m) {

			$mroles[$m->role_name] = 1; // собираю роли в отдельный массив

			if( !isset($ctrls[$m->ctrl_name]) )   // Ининциализируем
				$ctrls[$m->ctrl_name] = array();

			$ctrls[$m->ctrl_name][] = $m->slevel;
		}

		$this->view->mroles = array_keys($mroles);
		$this->view->ctrls  = $ctrls;
	}

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$data 	 = array();

		$entries = $this->pixie->db->query('select')
								->fields(array('C.name','ctrl_name'),
										 array('C.class','class'),
										 array('S.name','sect_name'),
										 array( $this->pixie->db->expr('IFNULL(L.slevel,0)'),'slevel'),
										 array('C.id','control_id ')
										 )
								->table('controllers','C')
								->join(array('sections','S'),array('S.id','C.section_id'),'LEFT')
								->join(array('page_roles','P'),array('C.id','P.control_id'),'LEFT')
								->join(array('slevels','L'),array('L.id','P.slevel_id'),'LEFT')
								->where('P.role_id', $this->_id)
								->order_by('S.id')
								->execute()
								->as_array();
print_r($entries);exit;
		// Ищем, какие контроллеры еще остались не в базе
		$controllers = $this->get_ctrl();
		$data = array();

		foreach($entries as $entry) {
			$data[] = array( $entry->sect_name,
							 $entry->ctrl_name,
							 $entry->slevel,
							 'DT_RowId' => 'tab-rights-'.$entry->class,
							 'DT_RowClass' => 'gradeA'
							);
			unset($controllers[$entry->class]);
		}

		if(is_array($controllers)) {
			// Если остались незадействованные контроллеры - мы их добавляем в конец задействованных
			foreach($controllers as $k => $v) {
				$data[] = array('-',
								$k,
								'0',
								'DT_RowId' => 'tab-rights-'.$entry->class,
								"DT_RowClass" => "gradeA gradeUU",
								);
			}
		}

		$this->response->body = json_encode($data);

    }

	public function action_single() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$view 		= $this->pixie->view('roles_view');
		$view->log 	= $this->getVar($this->logmsg,'');

		if( ! $this->request->param('id') )
			//return "<img class='lb' src=/roles.png />";
			return;

		$this->_id = $this->getVar($this->_id, $this->request->param('id'));

		$view->role = $this->pixie->db->query('select')->table('roles')
								->where('id', $this->_id)
								->execute()
								->current();


		$view->pages = $this->pixie->db->query('select')
								->fields(array('C.name','ctrl_name'),
										 array('S.name','sect_name'),
										 array( $this->pixie->db->expr('IFNULL(L.slevel,0)'),'slevel'),
										 array('C.id','control_id ')
										 )
								->table('controllers','C')
								->join(array('sections','S'),array('S.id','C.section_id'),'LEFT')
								->join(array('page_roles','P'),array('C.id','P.control_id'),'LEFT')
								->join(array('slevels','L'),array('L.id','P.slevel_id'),'LEFT')
								->where('P.role_id', $this->_id)
								->order_by('S.id')
								->execute()
								->as_array();

		$view->slevels = $this->pixie->db->query('select')->table('slevels')->execute()->as_array();


		$view->addscript = '<script type="text/javascript" src="/roles-2.js"></script>';

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
	}

	public function action_new() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$view 		= $this->pixie->view('roles_new');
		$view->log 	= $this->getVar($this->logmsg,'<strong>Ввод новой роли.</strong>');

		$view->pages = $this->pixie->db->query('select')
									->fields($this->pixie->db->expr('C.id AS ctrl_id, C.name AS ctrl_name, S.name AS sect_name'))
									->table('controllers','C')
									->join(array('sections','S'),array('S.id','C.section_id'),'LEFT')
									->order_by('section_id')
									->order_by('arrange')
									->execute();

		//$view->pages = $this->getVar($pages,array());

		// Доступ
		$view->slevels = $this->pixie->db->query('select')->table('slevels')->execute()->as_array();

		$view->addscript = '<script type="text/javascript" src="/roles-2.js"></script>';

		$this->response->body = $view->render();
	}

	public function action_add() {

        if ($this->request->method == 'POST') {

			if( $this->permissions != $this::WRITE_LEVEL ) {
				$this->noperm();
				return false;
			}

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


					// сначала удаляем
						$this->pixie->db->query('delete')
										->table('page_roles')
										->where('control_id', $page_id)
										->where('role_id', $params['role_id'])
										->execute();

					// добавляем снова
						$this->pixie->db->query('insert')
										->table('page_roles')
										->data($roleInPage)
										->execute();
				}
			}
			// Ошибки имели место - возвращаем форму
			if( isset( $this->logmsg ) ) {

				if ( $is_update ) {

					$this->_id = $params['role_id'];
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
