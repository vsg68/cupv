<?php

namespace App\Controller;

class Domains extends \App\Page {

    public function action_view() {


		$this->view->subview 		= 'domains';
		$this->view->script_file	= '<script type="text/javascript" src="/js/domains.js"></script>';
		$this->view->entries 		= $this->pixie->orm->get('domains')->find_all()->as_array();

        $this->response->body = $this->view->render();
    }

	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_domains');
		$view->tab  = $tab;

        $view->data		=  $this->pixie->orm->get('domains')->where('id',$this->_id)->find();
		$view->domains	=  $this->pixie->orm->get('domains')->where('domain_type',0)->find_all();

        $this->response->body = $view->render();
    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		$returnData  = array();

		try {
			$params['active'] 		= $this->getVar($params['active'],0);
			$params['all_enable']	= $this->getVar($params['all_enable'],0);
			$params['all_email']	= $this->getVar($params['all_email']) ? ($params['all_email'].'@'.$params['domain_name']): '';

			$tab = $params['tab'];
			unset($params['tab']);

			$is_update = $params['id'] ? true : false;

			// сохраняем модель
			// Если в запрос поместить true -  предполагается UPDATE
			$row = $this->pixie->orm->get('domains')
									->values($params, $is_update)
									->save();

			$id = ($params['id']) ? $params['id'] : $row->id;
			unset( $params['id'] );

			// Составляем правильный ответ
			$entry = array( $params['domain_name'] );

			if( $tab == 'domains' ) {
				array_push($entry,  $params['domain_notes'],
									$params['all_email'],
									$params['all_enable']);
			}
			elseif( $tab == 'aliases') {
				array_push($entry,  $params['delivery_to'],
									$params['domain_notes']);
			}
			elseif( $tab == 'transport') {
				array_push($entry,  $params['domain_notes'],
									$params['delivery_to']);
			}

			array_push($entry, $params['active']);

			$returnData 			= $entry;
			$returnData['DT_RowId']	= 'tab-'.$tab.'-'.$id;

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
			$delivery_to = $this->getVar($params['aname'],0);


			$aliases = $this->pixie->orm->get('domains')
										->where('delivery_to',$delivery_to)
										->find_all();

			$this->pixie->orm->get('domains')
								->where('id',$params['id'])
								->where('or',array('delivery_to',$delivery_to))   // и алиасы
								->delete_all();

			if( $params['tab'] == 'domains' ) {

				$val = array();
				foreach($aliases as $alias) {
					array_push($val, array('id' => $alias->id) );
				}

				$this->response->body = $val ? json_encode( $val ) : '';
			}
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }

}
?>
