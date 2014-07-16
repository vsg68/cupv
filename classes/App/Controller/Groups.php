<?php

namespace App\Controller;

class Groups extends \App\Page {

	public function action_view() {

		$this->view->script_file	= '<script type="text/javascript" src="/js/groups.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/css/users.css" type="text/css" />';  // !!!

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->entries = $this->pixie->orm->get('groups')->find_all();
		$this->view->subview = 'groups';

		$this->response->body	= $this->view->render();
	}

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		// подготовка запроса
		$entries = $this->pixie->orm->get('lists')
									->where('group_id', $this->_id)
									->users
									->find_all()->as_array(true);

		$data = $this->DTPropAddToObject($entries, 'lists', 'gradeB');
		$this->response->body = json_encode($data);
        }

	public function action_showTable() {
		// поскольку нет возможности preload связь has_many, то делаем две связи has_one & belongs_to
		$entries = $this->pixie->orm->get('groups')->with('lists') 			// has_one
							   ->with('lists.users')	// belongs_to
							   ->order_by('name')
							   ->find_all()->as_array(true);
		$data = array();

		foreach($entries as $entry) {
			$data[] = array($entry->name,
					$entry->lists->users->mailbox,
					$entry->lists->users->username,
					$entry->lists->users->active,
					"DT_RowClass" => "gradeA"
					);
		}
		
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

			$entries = $this->pixie->orm->get($tab)
										->where('id',$this->_id)
										->find();
		}
		elseif( $tab == 'lists' ) {

			$entries = $this->pixie->db->query('select')
									->fields(array('U.mailbox','name'),
											 array('U.username', 'note'),
											 array('U.id', 'id'),
											 array('L.group_id', 'user_id'))  // ??? может и не надо
									->table('users', 'U')
									->join(array('lists','L'),
										   array( array('L.user_id','U.id'),
												  array('L.group_id',$this->pixie->db->expr($this->_id))
												 ))
									->execute()
									->as_array();

			$view->pid = $this->_id;
		}

		$view->rows = $entries;
        $this->response->body = $view->render();
    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		if($params['tab'] != 'groups' )
			return;
		try {
			$tab  = $params['tab'];
			unset($params['tab']);

			$params['active'] = $this->getVar($params['active'],0);

			$is_update = $params['id'] ? true : false;

			// сохраняем модель
			// Если в запрос поместить true -  предполагается UPDATE
			$row = $this->pixie->orm->get($tab)
									->values($params, $is_update)
									->save();

			$id = $params['id'];
			unset( $params['id'] );

			$returnData  = array_values($params);
			$returnData['DT_RowId']		= 'tab-'.$tab.'-'.($id ? $id : $row->id); // Если id = 0 - вынимаем новый id

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}


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
			$this->pixie->orm->get('lists')->where('group_id',$this->_id)->delete_all();

			$obj_ids = is_array($this->request->post('obj_id')) ? $this->request->post('obj_id') : array();

			// вторым делом - вставляем
			foreach ($obj_ids as $obj_id ) {
				$this->pixie->orm->get('lists')
								 ->values(array('group_id' => $this->_id,'user_id' => $obj_id))
								 ->save();
			}

			// Последним делом - вынимаем
			$entries = $this->pixie->orm->get('groups')
										->where('id',$this->_id)
									    ->users
									    ->find_all();

			foreach($entries as $entry) {
				$data[] = array( $entry->username,
								 $entry->mailbox,
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

    public function action_select(){

        extract($this->request->get(), EXTR_OVERWRITE);

        if( isset($user_id) ) {
            $result = $this->pixie->db->query('select')
                                    ->table('lists',"L")
                                    ->fields($this->pixie->db->expr("L.id, G.name, G.active"))
                                    ->join(array("groups","G"),array("G.id","L.group_id"),"LEFT")
                                    ->where('L.user_id', $user_id)
                                    ->execute()->as_array();
        }
        else {
            $result = $this->pixie->db->query('select')
                                        ->table('groups')
                                        ->fields($this->pixie->db->expr("name as id, name as value"))
                                        ->where("active",1)
                                        ->execute()->as_array();
        }

        $this->response->body = json_encode($result);
    }
}
?>
