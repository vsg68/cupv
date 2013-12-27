<?php
namespace App\Controller;

class Alarms extends \App\Page {


    public function action_view1() {

		// Проверка легитимности пользователя и его прав
		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview 		= 'aliases';
		$this->view->script_file	= '<script type="text/javascript" src="/js/alarms.js"></script>';
		$this->view->css_file 		= '';

        $this->response->body	= $this->view->render();
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

        $this->response->body = $view->render();
    }


	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		$returnData  = array();

		$entry = array('alias_name' => $params['alias_name'],
					   'delivery_to'=> $params['delivery_to'],
					   'alias_notes'=> $this->getVar($params['alias_notes']),
					   'active'		=> $this->getVar($params['active'],0)
					 );

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

		// смотрим есть ли у нас пользователи по этим адресам
		// Извращение нужно для правильного занесения в таблицу;
		$tmp = array();
		foreach( array($params['alias_name'], $params['delivery_to']) as $mbox ) {

			$data = $this->pixie->db->query('select')
									->table( 'users' )
									->where('mailbox', $mbox)
									->execute()
									->current();

			array_push($tmp, $this->getVar($data->username,'N/A'));

		}

		array_unshift($entry, $tmp[0], '->', $tmp[1]);

		// Массив, который будем возвращать
		$returnData 				= array_values($entry);
		$returnData['DT_RowId']		= 'tab-aliases-'.$params['id'];


		$this->response->body = json_encode($returnData);
	}

	public function action_showTable() {

		$returnData = array();
		$alarms = $this->pixie->orm->get('alarms')->find_all()->as_array(true);

		$returnData["aaData"] = count($alarms) ? $this->DTPropAddToObject($alarms, 'alarms', ''): array();

        $this->response->body = json_encode($returnData);
	}

	public function action_delEntry() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		try {
			$this->pixie->orm->get($params['tab'] )
							->where('id',$params['id'])
							->delete_all();
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}
    }


}
?>
