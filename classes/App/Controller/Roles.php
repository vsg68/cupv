<?php

namespace App\Controller;

class Roles extends \App\Page {

    public function action_view() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview 		= 'roles';

		$this->view->script_file	= '<script type="text/javascript" src="/js/roles.js"></script>';
		$this->view->css_file 		= '';//<link rel="stylesheet" href="/roles.css" type="text/css" />';

		$this->view->entries = $this->pixie->db->query('select','admin')
											->table('roles')
											->execute();

        $this->response->body = $this->view->render();
    }

	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_roles');
		$view->pid	= $this->request->post('init');
		$view->tab  = $tab;

		if( preg_match('/^0\d*_(\d+)$/', $this->_id, $match) ) {
			$view->ctrl = $match[1];
			$this->_id  = 0;
		}


		$data = $this->pixie->db->query('select','admin')
										->table($tab)
										//->where( ( $tab == 'rights' ? 'control_' : '').'id',$this->_id )
										->where('id', $this->_id)
										->execute()
										->current();
//print_r($data);exit;
		if( $tab == 'rights' ) {

			$view->slevels = $this->pixie->db->query('select','admin')
											->table('slevels')
											->execute()
											->as_array();

			// передаем ссылку на контроллер для дальнейшего отслеживания

		}

	   $view->data = $data;
       $this->response->body = $view->render();
    }

	public function action_records() {

		if( ! $this->_id = $this->request->param('id'))
			return;

		try {
			$entries = $this->pixie->db->query('select','admin')
									->fields(array('S.name','sect_name'),
											 array('C.name','ctrl_name'),
											 array('C.class','ctrl_class'),
											 array($this->pixie->db->expr('COALESCE(L.name,"NONE")'),'slevel'),
											 array('C.active','c_active'),
											 array('P.role_id','role_id'),
											 array('C.id','ctrl_id'),
											 array('P.id','id')
											 )
									->table('controllers','C')
									->join(array('sections','S'),array('S.id','C.section_id'),'LEFT')
									->join( array('rights','P'),
												array(
														array('P.role_id',$this->pixie->db->expr($this->_id)),
														array('P.control_id','C.id')
													))
									->join(array('slevels','L'),array('L.id','P.slevel_id'),'LEFT')
									->order_by('S.name')
									->order_by('C.name')
									->execute()
									->as_array();

			$data = array();
			//$count = 0;
			foreach($entries as $entry) {

				$data[] = array( $entry->sect_name,
								 $entry->ctrl_name,
								 $entry->slevel,
								 $entry->c_active,
								 // Если такая запись есть - ее порядковый номер, если нет - составной
								 'DT_RowId'    => 'tab-rights-'.( $entry->role_id ? $entry->id : '0_'.$entry->ctrl_id),
								 'DT_RowClass' => ($entry->slevel == 'WRITE') ? 'gradeB' : ( ($entry->slevel == 'NONE') ? 'gradeX' : '')
								);
				//$count++;
			}

			$this->response->body = json_encode($data);
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }

	public function action_edit() {

		if( ! $params = $this->request->post() )
			return;

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		try {
			$returnData  = array();

			if($params['tab'] == 'roles' ) {
				$entry['note'] = $this->getVar($params['note']);
				$entry['name'] = $params['name'];
				$entry['active'] = $this->getVar($params['active'],0);
			}
			elseif($params['tab'] == 'rights' ) {

				if( preg_match('/^0\d*_(\d+)$/', $params['id'], $match) ) {
				// 	новая запись !
					$params['id'] 		 = 0;
					$entry['control_id'] = $match[1];
					$entry['role_id'] 	 = $params['role_id'];
				}

				$entry['slevel_id'] = $params['slevel_id'];
			}


			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert','admin')
								->table($params['tab'])
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id('admin');
			}
			else {

			// Существующая запись
				$this->pixie->db->query('update','admin')
								->table($params['tab'])
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}

			//Нужно отдать инфу в права в том же формате,
			//в котором оно представлено
			if( $params['tab'] == 'rights' ) {

				$req = $this->pixie->db->query('select','admin')
									->fields(array('S.name','sect_name'),
											 array('C.name','ctrl_name'),
											 array('L.name','slevel'),
											 array('C.active','active'),
											 array('P.control_id','control_id')
											 )
									->table('controllers','C')
									->join(array('sections','S'),array('S.id','C.section_id'),'LEFT')
									->join(array('rights','P'),array('P.control_id','C.id'))
									->join(array('slevels','L'),array('L.id','P.slevel_id'),'LEFT')
									->where('P.id', $params['id'])
									->execute()
									->current();

				// переводим ответ в нужный формат
				$entry = array($req->sect_name,
								$req->ctrl_name,
								$req->slevel,
								$req->active);

				$params['id'] = $req->control_id;
			}

			//print_r($entry); exit;
			$returnData 				= array_values($entry);
			//$returnData['DT_RowClass']  = ($req->slevel == 'WRITE') ? 'gradeB' : ( ($req->slevel == 'NONE') ? 'gradeX' : '');
			$returnData['DT_RowId']		= 'tab-'.$params['tab'].'-'.$params['id'];

			$this->response->body = json_encode($returnData);
//print_r($returnData);exit;
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}
	}


}
?>
