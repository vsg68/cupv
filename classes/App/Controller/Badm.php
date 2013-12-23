<?php

namespace App\Controller;

class Badm extends \App\ItBase {




	//~ public function action_addNewItem() {
//~
		//~ if( $this->permissions != $this::WRITE_LEVEL )
			//~ return $this->noperm();
//~
		//~ if( ! $params = $this->request->post() )
			//~ return;
//~
		//~ try {
			//~ $returnData = array();
//~
			//~ $records['entry'] = array_map(null, $params['fname'], $params['ftype'], $params['fval']);
//~
			//~ $data = array('data' => json_encode($records),
						  //~ 'pid' 	=> $params['pid'],
						  //~ 'name' 	=> $params['fval'][0], //NAME
						  //~ 'page' 	=> $this->ctrl);
//~
			//~ $row = $this->pixie->orm->get('names')
									 //~ ->values($data)
									 //~ ->save();
//~
			//~ $returnData	= array('title' => $data['name'],
								//~ 'key' 	=> $row->id);
//~
			//~ $this->response->body = json_encode($returnData);
		//~ }
		//~ catch (\Exception $e) {
			//~ $this->response->body = $e->getMessage();
			//~ return;
		//~ }
	//~ }



   	//~ public function action_edit() {
//~
		//~ if( $this->permissions != $this::WRITE_LEVEL )
			//~ return $this->noperm();
//~
		//~ if( ! $params = $this->request->post() )
			//~ return;
//~
		//~ try {
			//~ $row = $this->pixie->orm->get('names')
									//~ ->where('id', $params['pid'])
									//~ ->find();
//~
			//~ $records = json_decode($row->data);
//~
			//~ $data = isset($records->entry) ? $records->entry : array();
//~
			//~ // Если новая запись - порядковый номер делаем руками
			//~ $ord = ($params['id'] != '_0') ? $params['id'] : count($data) ;
//~
			//~ $data[$ord] = $params['fval'];
//~
			//~ $records->entry = $data;
//~
			//~ $row->data = json_encode($records);
			//~ $row->save();
//~
			//~ $returnData  = $this->DTPropAddToEntry($params['fval'], $params['tab'].'-'.$params['id'], 'gradeA');
//~
			//~ $this->response->body = json_encode($returnData);
		//~ }
		//~ catch (\Exception $e) {
//~
			//~ $this->response->body = $e->getMessage();
			//~ return;
		//~ }
	//~ }

}

?>
