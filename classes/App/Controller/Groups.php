<?php

namespace App\Controller;

class Domains extends \App\Page {


	public function action_showTable() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( !$tab 	= $this->request->param('id'); )
			return;

		if($tab = 'groups') {

			$entries = $this->pixie->db->query('select')
										->table('groups')
										->execute()
										->as_array();
			foreach($entries as $entry)	{
				$data[] = array($entry->name,
								$entry->note,
								$entry->active,
								'DT_RowId' => $entry->id
								);
			}
		}elseif($tab = 'groups') {

			$entries = $this->pixie->db->query('select')
										->fields(array('G.name', 'group'),
												 array('U.mailbox','login')
												 array('U.username', 'username')
												 array('U.active', 'active'))
										->table('users','U')
										->join(array('users_groups','G'),array('U.group_id','G.user_id'))
										->execute()
										->as_array();
			foreach($entries as $entry)	{
				$data[] = array($entry->name,
								$entry->note,
								'DT_RowId' => $entry->id
								);
			}


		}
		$view 		= $this->pixie->view('form_domains');
		$view->tab  = $tab;

	}

  	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_domains');
		$view->tab  = $tab;

        $view->data = $this->pixie->db->query('select')
										->table('domains')
										->where('id',$this->_id)
										->execute()
										->current();

		$view->domains = $this->pixie->db->query('select')
										->table('domains')
										->where('domain_type',0)
										->execute();

        $this->response->body = $view->render();
    }

	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		// Массив, который будем возвращать. Позиция важна

		if($params['tab'] == 'domains' ) {
			$entry = array( 'domain_name' 	=> $params['domain_name'],
							'delivery_to'	=> 'virtual',
							'domain_type'	=> 0,
							'domain_notes' 	=> $this->getVar($params['domain_notes']),
							'all_email'		=> $this->getVar($params['all_email']) ? ($params['all_email'].'@'.$params['domain_name']): '',
							'all_enable'	=> $this->getVar($params['all_enable'],0),
							'active'		=> $this->getVar($params['active'],0),
							);
		}
		elseif($params['tab'] == 'aliases' ) {
			$entry = array( 'domain_name' 	=> $params['domain_name'],
							'delivery_to'	=> $params['delivery_to'],
							'domain_type'	=> 1,
							'domain_notes' 	=> $this->getVar($params['domain_notes']),
							'active'		=> $this->getVar($params['active'],0),
							);
		}
		elseif($params['tab'] == 'transport' ) {
			$entry = array( 'domain_name' 	=> $params['domain_name'],
							'domain_notes' 	=> $this->getVar($params['domain_notes']),
							'delivery_to'	=> $params['delivery_to'],
							'domain_type'	=> 2,
							'active'		=> $this->getVar($params['active'],0),
							);
		}

		$returnData  = array();

		try {
			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert')
								->table('domains')
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id();

			}
			else {
			// Существующая запись
				$this->pixie->db->query('update')
								->table('domains')
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}

		// Составляем правильный ответ
		if( !$entry['domain_type'] ) {
			unset($entry['delivery_to']);
		}
		unset($entry['domain_type']);

		$returnData 			= array_values($entry);
		$returnData['DT_RowId']	= 'tab-'.$params['tab'].'-'.$params['id'];


		$this->response->body = json_encode($returnData);
	}

	public function action_delEntry() {


		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {
			$delivery_to = $this->getVar($params['aname'],0);

			$this->pixie->db->query('delete')
									->table('domains')
									->where('id',$params['id'])
									->where('or',array('delivery_to',$delivery_to))   // и алиасы
									->execute();

			if( $params['tab'] == 'domains' ) {

				$aliases = $this->pixie->db->query('select')
											->fields('id')
											->table('domains')
											->where('delivery_to',$delivery_to)
											->execute()
											->as_array();

				$val = array_values($aliases);
				$this->response->body = json_encode( $val );
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
