<?php

namespace App\Controller;

class Groups extends \App\Page {

	public function action_view() {

		$this->view->script_file	= '<script type="text/javascript" src="/js/groups.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/css/users.css" type="text/css" />';  // !!!

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$entries = $this->pixie->db->query('select')
							->fields(array('G.name', 'name'),
									 array('G.note', 'note'),
									 array('G.id', 'gid'),
									 array('G.active', 'g_active'),
									 array('U.mailbox','login'),
									 array('U.username', 'username'),
									 array('U.active', 'u_active'),
									 array('L.id', 'lid'))
							->table('groups','G')
							->join(array('lists','L'),array('G.id','L.group_id'))
							->join(array('users','U'),array('L.user_id','U.id'))
							->order_by('G.name')
							->execute()
							->as_array();

		$groups = array();
		$oldgroup = '';
		foreach( $entries as $entry ) {

			if( $oldgroup != $entry->gid ) {

				$groups[] = array('id' => $entry->gid,
								  'name' => $entry->name,
								  'note' => $entry->note,
								  'active' => $entry->g_active);

				$oldgroup = $entry->gid;
			}
		}

		$this->view->groups = $groups;
		$this->view->entries = $entries;
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

  	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;

		$view->data = $this->pixie->db->query('select')
										->table($tab)
										->where('id',$this->_id)
										->execute()
										->current();

        $this->response->body = $view->render();
    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		// Массив, который будем возвращать. Позиция важна

		if($params['tab'] == 'domains' ) {
			$entry = array( 'domain_name' 	=> $params['domain_name'],
							'delivery_to'	=> 'virtual',
							'domain_type'	=> 0,
							'domain_notes' 	=> $this->getVar($params['domain_notes']),
							'all_email'		=> $this->getVar($params['all_email']) ? ($params['all_email'].'@'.$params['domain_name']): '',
							'all_enable'	=> $this->getVar($params['all_enable'],0),
							'active'		=> $this->getVar($params['active'],0),
							);
		}
		elseif($params['tab'] == 'aliases' ) {
			$entry = array( 'domain_name' 	=> $params['domain_name'],
							'delivery_to'	=> $params['delivery_to'],
							'domain_type'	=> 1,
							'domain_notes' 	=> $this->getVar($params['domain_notes']),
							'active'		=> $this->getVar($params['active'],0),
							);
		}
		elseif($params['tab'] == 'transport' ) {
			$entry = array( 'domain_name' 	=> $params['domain_name'],
							'domain_notes' 	=> $this->getVar($params['domain_notes']),
							'delivery_to'	=> $params['delivery_to'],
							'domain_type'	=> 2,
							'active'		=> $this->getVar($params['active'],0),
							);
		}

		$returnData  = array();

		try {
			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert')
								->table('domains')
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id();

			}
			else {
			// Существующая запись
				$this->pixie->db->query('update')
								->table('domains')
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}

		// Составляем правильный ответ
		if( !$entry['domain_type'] ) {
			unset($entry['delivery_to']);
		}
		unset($entry['domain_type']);

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
			$delivery_to = $this->getVar($params['aname'],0);

			$this->pixie->db->query('delete')
									->table('domains')
									->where('id',$params['id'])
									->where('or',array('delivery_to',$delivery_to))   // и алиасы
									->execute();

			if( $params['tab'] == 'domains' ) {

				$aliases = $this->pixie->db->query('select')
											->fields('id')
											->table('domains')
											->where('delivery_to',$delivery_to)
											->execute()
											->as_array();

				$val = array_values($aliases);
				$this->response->body = json_encode( $val );
			}
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
		// Если массив не пустой - значит редактирование
		if( $grp_ids = $this->request->post('grp_id') ) {

			// Первым делом - удаляем
				$this->pixie->db->query('delete')
								->table('users_lists')
								->where('users_id',$this->_id)
								->execute();

			foreach ($grp_ids as $grp_id ) {
			// вторым делом - вставляем
				$this->pixie->db->query('insert')
								->table('users_lists')
								->data(array('users_id' => $this->_id,'lists_id' => $grp_id))
								->execute();
			}

		}

		// Последним делом - вынимаем


		//~ $groups = $this->pixie->db->query('select')
									//~ ->fields('L.name', 'L.note','L.id', 'UL.user_id')
									//~ ->table('groups','L')
									//~ ->join( array('lists','UL'),
											//~ array(
													//~ array('UL.user_id',$this->pixie->db->expr($this->_id)),
													//~ array('UL.group_id','L.id')
												//~ ),
											//~ 'left outer')
									//~ ->execute()
									//~ ->as_array();

		// 	Если у нас редактирование
		if( $grp_ids ) 	{

			foreach($groups as $group) {

				if( ! $group->users_id )
					continue;
				$data[] = array( $group->name,
								 $group->note,
								 'DT_RowClass' => 'gradeX'
								);
			}
			$this->response->body = json_encode($data);
		}
		else {

			$entries = $this->pixie->db->query('select')
									->fields(array('U.username','name'),
											 array('U.mailbox', 'note'),
											 array('L.user_id', 'id'),
											 array('L.group_id', 'gid'))  // ??? может и не надо
									->table('users', 'U')
									->join(array('lists','L'),
										   array( array('L.user_id','U.id'),
												  array('L.group_id',$this->pixie->db->expr($this->_id))
												 ))
									->execute()
									->as_array();

			$view = $this->pixie->view('form_lists');
			$view->entries = $entries;
			$view->pid 	  = $this->_id;
			$this->response->body = $view->render();
		}
	}
}
?>
