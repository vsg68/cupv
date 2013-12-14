<?php

namespace App\Controller;

class Badm extends \App\ItBase {


 	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {
			$tab  = $params['tab'];

			$params['pid']  = (isset($params['in_root']) && $params['in_root']) ? '0' : $params['pid'];
			$params['page'] = $this->request->param('controller');
			unset($params['tab'], $params['in_root']);
//~ print_r($params);exit;
			$is_update = $params['id'] ? true : false;

			// сохраняем модель
			// Если в запрос поместить true -  предполагается UPDATE
			$row = $this->pixie->orm->get('names')
									->values($params, $is_update)
									->save();

			$id = $params['id'];
			unset( $params['id'] );

			$returnData  = array('title' => $params['name'],
								 'isFolder' => true,
								 'key'   => ($id ? $id : $row->id));

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}


	}
 }

?>
