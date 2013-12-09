<?php
/*

 */
namespace App\Controller;

class Auth extends \App\Page {

    public function action_ShowTable() {

		$returnData = $data = array();

		$entries = $this->pixie->db
							->query('select','admin')
							->fields('id', 'login', 'note', array('R.name', 'role'), 'active' )
							->table('auth')
							->join(array('roles','R'),array('role_id','R.id'))
							->execute();

		foreach( $entries as $entry ) {
			$data[] = array($entry->login,
							$entry->note,
							$entry->role,
							$entry->active,
							"DT_RowId" => 'tab-auth-'.$entry->id
							);
		}

		$returnData['aaData'] = $data;
        $this->response->body	= json_encode($returnData);
    }

	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;

        $view->data = $this->pixie->db->query('select','admin')
										->table($tab)
										->where('id',$this->_id)
										->execute()
										->current();

        $view->roles = $this->pixie->db->query('select','admin')
										->table('roles')
										->execute();

       $this->response->body = $view->render();
    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		//$returnData  = array();

		$entry = array('role_id' => $params['role_id'],
					   'login'	 => $params['login'],
					   'note'	 => $this->getVar($params['note']),
					   'active'	 => $this->getVar($params['active'],0)
					   );

		// хешируем пароль средством модуля
		if( isset( $params['passwd']) && $params['passwd'] ) {
			$entry['passwd']  = $this->auth->provider('password')->hash_password($params['passwd']);
		}

		try {
			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert','admin')
								->table( $params['tab'] )
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id();

			}
			else {
			// Существующий пользователь
				$this->pixie->db->query('update','admin')
								->table( $params['tab'] )
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}

			// Что будем возвращать
			$entry = $this->pixie->db->query('select','admin')
										->fields('login', 'note', array('R.name', 'role'), 'active' )
										->table('auth')
										->join(array('roles','R'),array('role_id','R.id'))
										->where('id',$params['id'])
										->execute()
										->current();

			// Массив, который будем возвращать
			$returnData = array($entry->login,
								$entry->note,
								$entry->role,
								$entry->active,
								'DT_RowId' => 'tab-auth-'.$params['id']);

			$this->response->body 		= json_encode($returnData);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}
	}

	public function action_delEntry() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		try {
			$this->pixie->db->query('delete','admin')
							->table($params['tab'] )
							->where('id',$params['id'])
							->execute();
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}
    }


}

?>
