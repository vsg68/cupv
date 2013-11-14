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


	public function action_single() {

		$view 		= $this->pixie->view('domains_view');
		$view->log 	= $this->getVar($this->logmsg,'');

		if( ! $this->request->param('id') )
			//return "<img class='lb' src=/domains.png />";
			return;

		$this->domain_id = $this->getVar($this->domain_id, $this->request->param('id'));

		$domain = $this->pixie->db->query('select')
									->table('domains')
									->where('domain_id', $this->domain_id)
									->execute()
									->current();
		//Если ответ пустой
		if( ! count($domain) )
			return "<strong>Домена с ID ".$this->domain_id." не существует.</strong>";

		$view->domain = $domain;

		// Собираем алиасы домена
		$view->aliases = $this->pixie->db->query('select')
										->table('domains')
										->where('delivery_to', $domain->domain_name)
										->execute();

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
	}

	public function action_new() {

		$view 		= $this->pixie->view('domains_new');
		$view->log 	= $this->getVar($this->logmsg,'<strong>Ввод нового домена.</strong>');

		$this->response->body = $view->render();
	}

	public function action_add() {

        if ($this->request->method == 'POST') {

			$params = $this->request->post();

			$params['fname']  = $this->getVar($params['fname'], array());

			// Проверка на правильность заполнения (Новая запись)
			if( isset($params['domain_name']) )
				$this->sanitize($params['domain_name'], 'is_domain' );

			// Проверка типа домена
			if( isset($params['delivery_to']) ) {
				$this->sanitize( $params['delivery_to'], 'net');
				$params['domain_type'] = '2';
			}
			else {
				$params['delivery_to'] = 'virtual';
				$params['domain_type'] = '0';
			}

			$data_insert = array(
								'domain_name' 	=> $params['domain_name'],
								'delivery_to' 	=> $params['delivery_to'],
								'domain_type' 	=> $params['domain_type']
								);
			$data_update = array(
								'domain_notes'	=> $params['domain_notes'],
								'all_enable'	=> $this->getVar($params['all_enable'],0),
								'all_email'		=> isset( $params['all_email'] ) ? $params['all_email'].'@'.$params['domain_name'] : '',
								'active'		=> $this->getVar($params['active'],0)
								);

			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {

				// Если запись новая
				if( ! isset($params['domain_id']) ) {

					$this->pixie->db->query('insert')
									->table('domains')
									->data(array_merge($data_insert,$data_update))
									->execute();

					$params['domain_id'] = $this->pixie->db->insert_id();
				}
				// Если редактируем
				else {
					$this->pixie->db->query('update')
									->table('domains')
									->data($data_update)
									->where('domain_id', $params['domain_id'])
									->execute();
				}


				// Обработка алиасов
				foreach ($params['fname'] as $key=>$fname ) {

					$data_insert = array(
									'domain_name' => $fname,
									'delivery_to' => $params['domain_name'],
									);
					$data_update = array(
									'domain_type' => '1',
									'active'	  => $params['stat'][$key]
									);

					if( $params['stat'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')
										->table('domains')
										->where('domain_id',$params['fid'][$key])
										->execute();
					}
					elseif( $params['fid'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')
										->table('domains')
										->data(array_merge($data_insert, $data_update))
										->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')
										->table('domains')
										->data($data_update)
										->where('domain_id', $params['fid'][$key])
										->execute();
					}
				}
			}
			// Ошибки имели место - возвращаем форму
			if( isset( $this->logmsg ) ) {

				if ( isset($params['domain_id']) ) {

					$this->domain_id = $params['domain_id'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['domain_id'];

		}

	}


}
?>
