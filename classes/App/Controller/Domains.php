<?php

namespace App\Controller;

class Domains extends \App\Page {

    public function action_view() {


		$this->view->subview 		= 'domains';

		$this->view->script_file	= '<script type="text/javascript" src="/js/domains.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/css/domains.css" type="text/css" />';

		$entries = $this->pixie->db->query('select')
										->table('domains')
										->execute()
										->as_array();
		$this->view->entries = $entries;

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

		if($params['tab'] == 'domains' ) {
			$type = 0;
			$delivery_to = 'virtual';
		}
		elseif($params['tab'] == 'aliases' ) {
			$type = 1;
			$delivery_to = $params['domain_name'];
		}
		elseif($params['tab'] == 'trnsport' ) {
			$type = 2;
			$delivery_to = $params['delivery_to'];
		}

		$returnData  = array();

		// Массив, который будем возвращать
		$entry = array( 'domain_name' 	=> $params['domain_name'],
						'domain_notes' 	=> $this->getVar($params['domain_notes']),
						'delivery_to'	=> $delivery_to,
						'domain_type' 	=> $type,
						'all_email'	 	=> $params['all_email'].'@'.$params['domain'],
						'all_enable' 	=> $this->getVar($params['all_enable'],0),
						'active'		=> $this->getVar($params['active'],0)
						);

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

		unset($entry['domain_type']);

		if($params['tab'] == 'domains' ) {
			unset($entry['delivery_to']);
		}
		else {
			unset($entry['all_email']);
			unset($entry['all_enable']);
		}

		$returnData 			= array_values($entry);
		$returnData['DT_RowId']	= 'tab-'.$params['tab'].'-'.$params['id'];


		$this->response->body = json_encode($returnData);
	}

	public function action_delEntry() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		$returnData = array();

		$aliases = $this->pixie->db->query('select')
							->fields('id')
							->table('domains')
							->where('delivery_to',$params['aname'])
							->execute()
							->as_array();

		$this->pixie->db->query('delete')
						->table('domains')
						->where('id',$params['id'])
						->where('or',array('delivery_to',$params['aname']))   // и алиасы
						->execute();

		return $returnData	= json_encode($aliases);

    }

}
?>
