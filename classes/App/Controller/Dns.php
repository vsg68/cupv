<?php

namespace App\Controller;

class Dns extends \App\Page {

	public function action_view() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->script_file	= '<script type="text/javascript" src="/js/dns.js"></script>';
		$this->view->entries 		= $this->pixie->orm->get('dns')->find_all();
		$this->view->subview 		= 'dns';

		$this->response->body	= $this->view->render();
	}

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$entries = $this->pixie->orm->get('records')->where('domain_id',$this->_id)->find_all();

		$data = array();
		foreach($entries as $entry)	{

			$data[] = array($entry->name,
							$entry->type,
							$entry->content,
							$entry->ttl,
							'DT_RowId' => 'tab-records-'.$entry->id,
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
		$view 		= $this->pixie->view('form_dns');
		$init 		= $this->request->post('init');
		$view->tab  = $tab;
		// Запрос к бд
		$view->data = $this->pixie->orm->get($tab)->where('id',$this->_id)->find();

		// Для новой записи
		if( !$this->_id && $init) {
			$view->data->domain_id = $init;
		}


        $this->response->body = $view->render();
    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		try {
			$tab = $params['tab'];
			unset($params['tab']);

			$not_new = $params['id'] ? true : false;

			// вынимаем модель
			$row = $this->pixie->orm->get($tab)->values($params, $not_new)->save();


			$id = $params['id'];
			unset( $params['domain_id'], $params['id'] );

			// отдаем
			$returnData 				= array_values($params);
			$returnData['DT_RowClass']  = 'gradeB';
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
			$entry = $this->pixie->orm->get($params['tab'])->where( 'id', $params['id'])->find();
			// если это запись домена
			if ( $params['tab'] == 'dns' ) {
				$entry->records->delete_all();
			}

			$entry->delete();
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }

}
?>
