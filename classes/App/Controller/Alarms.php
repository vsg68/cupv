<?php
namespace App\Controller;

class Alarms extends \App\Page {


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

		if($params['tab'] != 'alarms' )
			return;
		try {
			$tab  = $params['tab'];
			unset($params['tab']);

			$params['active'] = $this->getVar($params['active'],0);

			// Задача в этот период решена - Изменяем дату следющего запуска
			if( isset($params['done']) ) {
				$params['nextlaunch'] = date('Y-m-d', strtotime($params['nextlaunch']) + $params['period']*86400 );
				unset($params['done']);
			}

			$is_update = $params['id'] ? true : false;

			// сохраняем модель
			// Если в запрос поместить true -  предполагается UPDATE
			$row = $this->pixie->orm->get($tab)
									->values($params, $is_update)
									->save();

			$id = $params['id'];
			unset( $params['id'] );

			$params['DT_RowId']		= 'tab-'.$tab.'-'.($id ? $id : $row->id); // Если id = 0 - вынимаем новый id

			$this->response->body = json_encode($params);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}
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
