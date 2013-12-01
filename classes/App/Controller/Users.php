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
							 'DT_RowClass' => 'gradeA'
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
							 'DT_RowId' => 'tab-aliases-'.$list->id,
							 'DT_RowClass' => 'gradeX'
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

		if( $this->permissions == $this::NONE_LEVEL )
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

		if( ! $this->_id = $this->request->param('id'))
			return;

		$entry = $data = array();
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
		$groups = $this->pixie->db->query('select')
									->fields('L.name', 'L.note','L.id', 'UL.users_id')
									->table('lists','L')
									->join( array('users_lists','UL'),
											array(
													array('UL.users_id',$this->pixie->db->expr($this->_id)),
													array('UL.lists_id','L.id')
												),
											'left outer')
									->execute()
									->as_array();

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
			$view = $this->pixie->view('form_lists');
			$view->groups = $groups;
			$view->pid 	  = $this->_id;
			$this->response->body = $view->render();
		}
	}

}

?>
