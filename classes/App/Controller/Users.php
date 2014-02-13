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
				$view->domains = $this->pixie->orm->get('domains')
												->where('delivery_to','virtual')
												->order_by('domain_name')
												->find_all();
			}

			$view->data = $this->pixie->orm->get($tab)
											->where('id',$this->_id)
											->find();

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


		$fulldata['aliases'] =  $this->DTPropAddToObject($aliases, 'aliases', 'gradeB');

		$data  = array();

		$lists = $this->pixie->orm->get('users')
								  ->where('id',$this->_id)
								  ->groups
								  ->find_all()->as_array(true);

		$fulldata['lists'] 	 =  $this->DTPropAddToObject($lists, 'lists', 'gradeA');


		$this->response->body = json_encode($fulldata);

    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		$tab = $params['tab'];
		if( $tab == 'users' ) {
			$params['mailbox'] 		= $params['login'].'@'.$params['domain'];
			$params['imap_enable'] 	= $this->getVar($params['imap_enable'],0);
			// принудительно
			$params['acl_groups'] 	= '';
			
			if( ! $params['path'] )	{
				$params['path'] = NULL;
			}

			if( isset($params['password']) ) {
				$params['md5password'] 	= md5($params['password']);
			}
		}

		if( $tab != 'lists' ) {
			$params['active']		= $this->getVar($params['active'],0);
		}

		unset($params['tab'],$params['login'],$params['domain']);

		try {

			$is_update = $params['id'] ? true : false;

			// Если в запрос поместить true -  предполагается UPDATE
			$row = $this->pixie->orm->get($tab)
									->values($params, $is_update)
									->save();

			$id = $params['id'];
			unset( $params['id'] );

			// Рисуем класс
			if( $tab == 'aliases')
				$params['DT_RowClass']	= 'gradeA';

			$params['DT_RowId']	= 'tab-'.$tab.'-'. ($id ? $id : $row->id);

			$this->response->body = json_encode($params);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}
	}

	public function action_showTable() {

		$returnData = array();
		$users = $this->pixie->orm->get('users')->find_all()->as_array(true);

		$returnData["aaData"] = $this->DTPropAddToObject($users, 'users', '');
        $this->response->body = json_encode($returnData);
	}

	public function action_delEntry() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		try {
			$this->pixie->orm->get($params['tab'])
							->where('id',$params['id'])
							->delete_all();

			// Если удаляем из пользователей - удаляем все связнанные значения
			// Но могут оставаться алиасы - тогда об этом предупреждаем
			if( $params['tab'] == 'users' ) {
				$this->pixie->orm->get('aliases')
								->where('delivery_to',$params['aname'])
								->delete_all();

				$aliases = $this->pixie->orm->get('aliases')
											->where('alias_name',$params['aname'])
											->find_all();

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
			$this->pixie->orm->get('lists')
							->where('user_id',$this->_id)
							->delete_all();

			$obj_ids = is_array($this->request->post('obj_id')) ? $this->request->post('obj_id') : array();

			// вторым делом - вставляем
			foreach ($obj_ids as $obj_id ) {

				$this->pixie->orm->get('lists')
								->values(array('user_id' => $this->_id,'group_id' => $obj_id))
								->save();
			}

			// Последним делом - вынимаем
			$entries = $this->pixie->orm->get('users')
										->where('id', $this->_id)
										->groups
										->find_all()->as_array(true);

			$data = $this->DTPropAddToObject($entries, '', 'gradeA');

//print_r($data);exit;
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
