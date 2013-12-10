<?php

namespace App\Controller;

class Users extends \App\Page {

  	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$init		= $this->request->post('init');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;

		if( $tab == 'lists' ) {

			$entries = $this->pixie->db->query('select')
									->fields(array('G.name','name'),
											 array('G.note', 'note'),
											 array('G.id', 'id'),
											 array('L.user_id', 'user_id'))  // ??? может и не надо
									->table('groups', 'G')
									->join(array('lists','L'),
										   array( array('L.group_id','G.id'),
												  array('L.user_id',$this->pixie->db->expr($this->_id))
												 ))
									->execute()
									->as_array();

			$view->rows = $entries;
			$view->pid 	   = $this->_id;
		}
		else {
			if( $tab == 'users' ) {
				$view->domains = $this->pixie->db->query('select')
											->table('domains')
											->group_by('domain_name')
											->where('delivery_to','virtual')
											->execute();
			}

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
		}

       $this->response->body = $view->render();
    }

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$fulldata = array();
		$data 	 = array();
		$aliases = $this->pixie->db->query('select')
										->fields('id','alias_name', 'delivery_to', 'active')
										->table('aliases','A')
										->join(array('users','U1'),array('U1.mailbox','A.alias_name'))
										->join(array('users','U2'),array('U2.mailbox','A.delivery_to'))
										->where('U1.id',$this->_id)
										->where('or',array('U2.id',$this->_id))
										->execute();

		foreach($aliases as $alias)
			$data[] = array( $alias->alias_name,
							 $alias->delivery_to,
							 $alias->active,
							 'DT_RowId' => 'tab-aliases-'.$alias->id,
							 'DT_RowClass' => 'gradeB'
							);

		$fulldata['aliases'] =  $data;


		$data  = array();
		$lists = $this->pixie->db->query('select')
								->fields('U.id','G.id', 'G.name', 'G.note')
								->table('lists','A')
								->join(array('users','U'),array('U.id','A.user_id'))
								->join(array('groups','G'),array('G.id','A.group_id'))
								->where('U.id',$this->_id)
								->execute();

		foreach($lists as $list)
			$data[] = array( $list->name,
							 $list->note,
							 'DT_RowId' => 'tab-lists-'.$list->id,
							 'DT_RowClass' => 'gradeA'
							);

		$fulldata['lists'] 	 =  $data;


		$this->response->body = json_encode($fulldata);

    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		$returnData  = array();

		if( $params['tab'] == 'users') {
			// Массив, который будем возвращать
			$entry = array( 'username' 		=> $params['username'],
							'mailbox'	 	=> $params['login'].'@'.$params['domain'],
							'password' 		=> $params['password'],
							'md5password' 	=> md5($params['password']),
							'allow_nets' 	=> $this->getVar($params['allow_nets'],'192.168.0.0/24'),
							'path'			=> $this->getVar($params['path']),
							'acl_groups' 	=> $this->getVar($params['acl_groups']),
							'imap_enable' 	=> $this->getVar($params['imap'],0),
							'active'		=> $this->getVar($params['active'],0)
							);
		}
		else {
			$entry = array('alias_name' => $this->getVar($params['alias_name']),
						   'delivery_to'=> $this->getVar($params['delivery_to']),
						   'active'		=> $this->getVar($params['active'],0)
						 );
		}

		try {
			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert')
								->table( $params['tab'] )
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id();

			}
			else {
			// Существующий пользователь
				$this->pixie->db->query('update')
								->table( $params['tab'] )
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}


		// для правильного отображения строки в таблице
		if( $params['tab'] == 'users')
			$entry['md5password'] = $params['domain'];

		// Массив, который будем возвращать
		$returnData = array_values($entry);

		// Рисуем класс
		if( $params['tab'] == 'aliases')
			$returnData['DT_RowClass']	= 'gradeA';

		$returnData['DT_RowId']	= 'tab-'.$params['tab'].'-'.$params['id'];


		$this->response->body = json_encode($returnData);
	}

	public function action_showTable() {

		$returnData["aaData"] = array();
		$users = $this->pixie->db->query('select')
								->fields($this->pixie->db->expr('
												id,
												username,
												mailbox,
												SUBSTRING_INDEX(mailbox, "@", -1) AS domain,
												password,
												allow_nets AS nets,
												IFNULL(path,"") AS path,
												IFNULL(acl_groups,"") AS groups,
												IFNULL(imap_enable,0) AS imap,
												active'))
								->table('users')
								->execute()
								->as_array();

		foreach($users as $user) {
			$returnData["aaData"][] = array($user->username,
											 $user->mailbox,
											 $user->domain,
											 $user->password,
											 $user->nets,
											 $user->path,
											 $user->groups,
											 $user->imap,
											 $user->active,
											 'DT_RowId'=>'tab-users-'.$user->id
											);
		}

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

			// Если удаляем из пользователей - удаляем все связнанные значения
			// Но могут оставаться алиасы - тогда об этом предупреждаем
			if( $params['tab'] == 'users' ) {
				$this->pixie->db->query('delete')
								->table('aliases')
								->where('delivery_to',$params['aname'])
								->execute();

				$aliases = $this->pixie->db->query('select')
								->table('aliases')
								->where('alias_name',$params['aname'])
								->execute()
								->as_array();

				// такие алиасы есть - предупреждаем
				if( $aliases ) {

					$view = $this->pixie->view('form_alert');
					$view->aliases = $aliases;

					$this->response->body = $view->render();
				}
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
		try {
			// Первым делом - удаляем
			$this->pixie->db->query('delete')
							->table('lists')
							->where('user_id',$this->_id)
							->execute();

			$obj_ids = is_array($this->request->post('obj_id')) ? $this->request->post('obj_id') : array();

			// вторым делом - вставляем
			foreach ($obj_ids as $obj_id ) {
				$this->pixie->db->query('insert')
								->table('lists')
								->data(array('user_id' => $this->_id,'group_id' => $obj_id))
								->execute();
			}

			// Последним делом - вынимаем
			$entries = $this->pixie->db->query('select')
										->table('groups','G')
										->join( array('lists','UL'), array('UL.group_id','G.id') )
										->join( array('users','U'), array('UL.user_id','U.id') )
										->where('U.id',$this->_id)
										->execute()
										->as_array();

			foreach($entries as $entry) {
				$data[] = array( $entry->name,
								 $entry->note,
								 'DT_RowClass' => 'gradeA'
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
