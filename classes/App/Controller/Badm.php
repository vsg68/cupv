<?php

namespace App\Controller;

class Badm extends \App\ItBase {

	protected function assocMaps ($fname, $ftype, $fval) {

			return array( 'fname' => $fname,'ftype' => $ftype, 'fval' => $fval);
	}

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {
			$row = $this->pixie->orm->get('names')
									->where('id', $params['pid'])
									->find();

			$records = json_decode($row->records);

			// Если новая запись - порядковый номер делаем руками
			$ord = ($params['id'] != '_0') ? $params['id'] : count($records) ;

			$records[$ord] = array('fname' => $params['fname'],
								   'ftype' => $params['ftype'],
								   'fval'  => $params['fval']);

			$row->records = json_encode($records);
			$row->save();

			$returnData  = array($params['fname'],
								 $params['fval'],
								 'DT_RowClass' => 'gradeA',
								 'DT_RowID' => 'tab-rec-'.$params['id']);

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {

			$this->response->body = $e->getMessage();
			return;
		}
	}


	public function action_addNewItem() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {
			$returnData = array();

			$records['entry'] = array_map( array($this,"assocMaps"), $params['fname'], $params['ftype'], $params['fval']);

			$data = array('records' => json_encode($records),
						  'pid' 	=> $params['pid'],
						  'name' 	=> $params['fval'][0], //NAME
						  'page' 	=> $this->ctrl);

			$row = $this->pixie->orm->get('names')
									 ->values($data)
									 ->save();

			$returnData	= array('title' => $data['name'],
								'key' 	=> $row->id);

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}
	}




}

?>
