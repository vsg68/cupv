<?php

namespace App\Controller;

class Groups extends \App\Page {

	public function action_view() {

		$this->view->script_file	= '<script type="text/javascript" src="/js/groups.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/css/users.css" type="text/css" />';  // !!!

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->entries = $this->pixie->db->query('select')
												->table('groups','G')
												->execute();
		$this->view->subview = 'groups';

		$this->response->body	= $this->view->render();
	}

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$data 	 = array();

		$entries = $this->pixie->db->query('select')
									->fields(array('U.mailbox','login'),
											 array('U.username', 'username'),
											 array('U.active', 'active'),
											 array('L.id', 'lid'))
									->table('users','U')
									->join(array('lists','L'),array('U.id','L.user_id'))
									->where('L.group_id', $this->_id)
									->execute()
									->as_array();

		foreach($entries as $entry)	{

			$data[] = array($entry->login,
							$entry->username,
							$entry->active,
							'DT_RowId' => 'tab-lists-'.$entry->lid,
							'DT_RowClass' => 'gradeB');
		}

		$this->response->body = json_encode($data);
    }

	public function action_showTable() {

		$entries = $this->pixie->db->query('select')
							->fields(array('G.name', 'name'),
									 array('U.mailbox','login'),
									 array('U.username', 'username'),
									 array('U.active', 'active'))
							->table('groups','G')
							->join(array('lists','L'),array('G.id','L.group_id'))
							->join(array('users','U'),array('L.user_id','U.id'))
							->order_by('G.name')
							->execute();

		$data = array();

		foreach($entries as $entry)
			$data[] = array($entry->name,
							$entry->login,
							$entry->username,
							$entry->active,
							"DT_RowClass" => "gradeA"
							);

		$retutnData = array("sEcho" => 1,
							"iTotalRecords" => sizeof($data),
							"iTotalDisplayRecords" => sizeof($data),
							"aaData" => $data
							);

		$this->response->body = json_encode($retutnData);
	}

  	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;

		if( $tab == 'groups' ) {
			$view->data = $this->pixie->db->query('select')
											->table($tab)
											->where('id',$this->_id)
											->execute()
											->current();
		}
		elseif( $tab == 'lists' ) {

			$entries = $this->pixie->db->query('select')
									->fields(array('U.username','name'),
											 array('U.mailbox', 'note'),
											 array('U.id', 'id'),
											 array('L.group_id', 'gid'))  // ??? может и не надо
									->table('users', 'U')
									->join(array('lists','L'),
										   array( array('L.user_id','U.id'),
												  array('L.group_id',$this->pixie->db->expr($this->_id))
												 ))
									->execute()
									->as_array();

			$view->entries = $entries;
			$view->pid 	   = $this->_id;
		}

        $this->response->body = $view->render();
    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		// Массив, который будем возвращать. Позиция важна

		if($params['tab'] != 'groups' )
			return;

		$entry = array( 'name' 	=> $params['name'],
						'note' 	=> $this->getVar($params['note']),
						'active'=> $this->getVar($params['active'],0),
						);

		$returnData  = array();
		try {
			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert')
								->table($params['tab'])
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id();

			}
			else {
			// Существующая запись
				$this->pixie->db->query('update')
								->table($params['tab'])
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}

		$returnData 			= array_values($entry);
		$returnData['DT_RowId']	= 'tab-'.$params['tab'].'-'.$params['id'];


		$this->response->body = json_encode($returnData);
	}

	public function action_delEntry() {


		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {

			$this->pixie->db->query('delete')
									->table($params['tab'])
									->where('id',$params['id'])
									->execute();
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }

    public function action_edGroup() {

		if( $this->permissions == $this::NONE_LEVEL )
				return $this->noperm();

		// Родительский ID
		if( ! $this->_id = $this->request->param('id'))
			return;

		$entries = $data = array();
		try {
			// Первым делом - удаляем
			$this->pixie->db->query('delete')
							->table('lists')
							->where('group_id',$this->_id)
							->execute();

			$obj_ids = is_array($this->request->post('obj_id')) ? $this->request->post('obj_id') : array();

			// вторым делом - вставляем
			foreach ($obj_ids as $obj_id ) {
				$this->pixie->db->query('insert')
								->table('lists')
								->data(array('group_id' => $this->_id,'user_id' => $obj_id))
								->execute();
			}

			// Последним делом - вынимаем
			$entries = $this->pixie->db->query('select')
										->fields(array('U.username','name'),
												 array('U.mailbox', 'note'),
												 array('U.active', 'active'))
										->table('users','U')
										->join( array('lists','UL'), array('UL.user_id','U.id') )
										->join( array('groups','G'), array('UL.group_id','G.id') )
										->where('G.id',$this->_id)
										->execute()
										->as_array();

			foreach($entries as $entry) {
				$data[] = array( $entry->name,
								 $entry->note,
								 $entry->active,
								 'DT_RowClass' => 'gradeB'
								);
			}

			$this->response->body = json_encode($data);
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

	}
}
?>
