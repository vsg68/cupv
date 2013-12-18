<?php

namespace App\Controller;

class Badm extends \App\ItBase {


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

	public function action_showNewForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$view = $this->pixie->view('form_'.$this->request->param('controller'));

		if( ! $view->pid = $this->request->param('id') )
			return;

        $this->response->body = $view->render();
    }
}

?>
