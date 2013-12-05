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

		$data 	 = array();
/*
		// сначала выбираем нужную запись, а потом по ней вынимаем из связанной таблице все записи
		$dns = $this->pixie->orm->get('dns',$this->_id)->find();
		$entries = $dns->records->count_all();
*/
		// А можно вот так
		$entries = $this->pixie->orm->get('records')->where('domain_id',$this->_id)->find_all();

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

	public function action_showTable() {

		$entries = $this->pixie->db->query('select')
							->fields(array('G.name', 'name'),
									 array('U.mailbox','login'),
									 array('U.username', 'username'),
									 array('U.active', 'active'))
							->table('groups','G')
							->join(array('lists','L'),array('G.id','L.group_id'))
							->join(array('users','U'),array('L.user_id','U.id'))
							->order_by('G.name')
							->execute();

		$data = array();

		foreach($entries as $entry)
			$data[] = array($entry->name,
							$entry->login,
							$entry->username,
							$entry->active,
							"DT_RowClass" => "gradeA"
							);

		$retutnData = array("sEcho" => 1,
							"iTotalRecords" => sizeof($data),
							"iTotalDisplayRecords" => sizeof($data),
							"aaData" => $data
							);

		$this->response->body = json_encode($retutnData);
	}

  	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_dns');
		$view->tab  = $tab;
		// Запрос к бд
		$view->data = $this->pixie->orm->get($tab)->where('id',$this->_id)->find();

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

			// меняем значения
			//~ if($params['tab'] == 'records' ) {
//~
					//~ $entry->name => $params['name'];
					//~ $entry->type => $params['type'];
					//~ $entry->domain_id => $params['domain_id'];
					//~ $entry->content => $params['content'];
				//~ $entry = array('name' 	   => $params['name'],
							   //~ 'type' 	   => $params['type'],
							   //~ 'content'   => $params['content'],
							   //~ 'domain_id' => $params['domain_id'],
							   //~ 'ttl'	   => $params['ttl'],
							   //~ 'id'		   => $params['id'],
							   //~ );
			//~ }
			//~ elseif( $params['tab'] == 'dns' ) {
						//~ $entry->name => $params['name'];
						//~ $entry->master => $params['type'];
						//~ $entry->last_check => $this-getVar($params['last_check']);
				//~ $entry = array('name' 		=> $params['name'],
							   //~ 'master'		=> $params['type'],
							   //~ 'last_check' => $this-getVar($params['last_check']),
							   //~ 'id'			=> $params['id'],
							   //~ );
			//~ }

			$not_new = $params['id'] ? true : false;

// вынимаем модель
			$row = $this->pixie->orm->get($tab)->values($params, $not_new)->save();


			unset( $params['domain_id']);
			$id = $params['id'];
			unset( $params['id']);

			// отдаем
			$returnData 			= array_values($params);
			$returnData['DT_RowId']	= 'tab-'.$tab.'-'.($id ? $id : $row->id); // Если id = 0 - вынимаем новый id


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

			$this->pixie->db->query('delete')
									->table($params['tab'])
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
