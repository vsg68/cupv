<?php
/*

 */
namespace App\Controller;

class Auth extends \App\Page {

    public function action_ShowTable() {

		$returnData = $data = array();

		$entries = $this->pixie->orm->get('auth')->with('roles')->find_all();

		foreach( $entries as $entry ) {
			$data[] = array($entry->login,
							$entry->note,
							$entry->roles->name,
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

        $view->data = $this->pixie->orm->get($tab)
									   ->where('id',$this->_id)
									   ->find();

        $view->roles = $this->pixie->orm->get('roles')->find_all();

       $this->response->body = $view->render();
    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		// хешируем пароль средством модуля
		if( isset( $params['passwd']) && $params['passwd'] ) {
			$passwd['passwd']  = $this->auth->provider('password')->hash_password($params['passwd']);
		}

		$params['active'] = $this->getVar($params['active'],0);

		try {

			$tab = $params['tab'];
			unset($params['tab']);

			$is_update = $params['id'] ? true : false;

			// сохраняем модель
			// Если в запрос поместить true -  предполагается UPDATE
			$row = $this->pixie->orm->get($tab)
									->values($params, $is_update)
									->save();

			$id = ($params['id']) ? $params['id'] : $row->id;
			unset( $params['id'] );

			// Что будем возвращать
			$entry = $this->pixie->orm->get('auth')->where('id',$id)->find();

			// Массив, который будем возвращать
			$returnData = array($entry->login,
								$entry->note,
								$entry->roles->name,	// Не указывал в запросе - она уже есть из модели !!!
								$entry->active,
								'DT_RowId' => 'tab-auth-'.$id);

			$this->response->body 		= json_encode($returnData);
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
			$this->pixie->orm->get($params['tab'])->where('id',$params['id'])->delete_all();
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}
    }


}

?>
