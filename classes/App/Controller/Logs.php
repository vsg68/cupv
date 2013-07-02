<?php
/*

 */
namespace App\Controller;

class Logs extends \PHPixie\Controller {

   private $logmsg;


    public function action_view() {

        $view = $this->pixie->view('main');

		$view->subview 		= 'logs_main';

		$view->script_file	= '<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>'.
								'<script type="text/javascript" src="/logs.js"></script>';
		$view->css_file 	= '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />'.
								'<link rel="stylesheet" href="/logs.css" type="text/css" />';

		$view->groups_block 	= ''; //$this->action_single();
        $this->response->body	= $view->render();
    }


	public function action_single() {

		$view 		= $this->pixie->view('groups_view');
		$view->log 	= isset($this->logmsg) ?  $this->logmsg : '';


		if( ! $this->request->get('name') )
			return;

		if( ! isset($this->domain_id) )
			$this->domain_id = $this->request->get('name');

		$domain = $this->pixie->db
								->query('select')->table('domains')
								->where('domain_id', $this->domain_id)
								->execute()
								->current();

		//Если ответ пустой
		if( ! count($domain) )
			return "<strong>Домена с ID ".$this->domain_id." не существует.</strong>";

		$view->domain = $domain;

		// Собираем алиасы домена
		$view->aliases = $this->pixie->db
									->query('select')->table('domains')
									->where('delivery_to', $domain->domain_name)
									->execute();

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
	}

	public function action_new() {

		$view 		= $this->pixie->view('domains_new');
		$view->log 	= isset($this->logmsg) ?  $this->logmsg : '';

		if( ! isset($this->logmsg) )

			$view->log = '<strong>Ввод нового домена.</strong>';

		$this->response->body = $view->render();
	}

	public function action_add() {

        if ($this->request->method == 'POST') {

			$params = $this->request->post();

			if( ! isset( $params['active'] ) )  $params['active'] = 0;

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

			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {

				// Если запись новая
				if( ! isset($params['domain_id']) ) {

					$this->pixie->db
									->query('insert')->table('domains')
									->data(array(
												'domain_name' 	=> $params['domain_name'],
												'delivery_to' 	=> $params['delivery_to'],
												'domain_type' 	=> $params['domain_type'],
												'domain_notes'	=> $params['domain_notes'],
												'active'		=> $params['active']
												))
									->execute();

					$params['domain_id'] = $this->pixie->db->insert_id();

					$params['dom_alias'] = $params['domain_name'];
				}
				// Если редактируем
				else {
					$this->pixie->db
									->query('update')->table('domains')
									->data(array(
												'domain_notes'	=> $params['domain_notes'],
												'active'		=> $params['active'],
												))
									->where('domain_id', $params['domain_id'])
									->execute();
				}

			}

			// Обработка алиасов
			foreach ($params['dom'] as $key=>$alias ) {

				if( $params['dom_st'][$key] == 2 ) {
				// Удаление
					$this->pixie->db->query('delete')->table('domains')
									->where('domain_id',$params['dom_id'][$key])
									->execute();
				}
				elseif( $params['dom_id'][$key] == 0 ) {
				// Новый
					$this->pixie->db->query('insert')->table('domains')
									->data(array(
										'domain_name' => $alias,
										'delivery_to' => $params['dom_alias'],
										'domain_type' => '1',
										'active'	  => $params['dom_st'][$key]
									))->execute();
				}
				else {
				// Изменение
					$this->pixie->db->query('update')->table('domains')
									->data(array(
										'domain_name' => $alias,
										'active'	 => $params['dom_st'][$key]
									))
									->where('domain_id', $params['dom_id'][$key])
									->execute();
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
