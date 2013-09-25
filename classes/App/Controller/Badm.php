<?php

namespace App\Controller;

class Badm extends \App\ItBase {


    public function action_view() {



		$this->view->script_file  = '<script type="text/javascript" src="/jquery-ui.custom.min.js"></script>';
		$this->view->script_file .= '<script type="text/javascript" src="/jquery.dynatree.js"></script>';
		$this->view->script_file .= '<script type="text/javascript" src="/badm.js"></script>';

		$this->view->css_file = '<link rel="stylesheet" href="/skin/ui.dynatree.css" type="text/css" />';
		$this->view->css_file .= '<link rel="stylesheet" href="/badm.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        //~ if( $this->permissions == $this::NONE_LEVEL ) {
			//~ $this->noperm();
			//~ return false;
		//~ }

		$this->view->subview = 'badm_main';

		$this->view->badm_block = '';//$this->action_single();

        $this->response->body = $this->view->render();
    }

	//~ public function action_new() {
//~
		//~ if( $this->permissions == $this::NONE_LEVEL ) {
			//~ $this->noperm();
			//~ return false;
		//~ }
//~
        //~ $view = $this->pixie->view('bserver_new');
//~
		//~ $view->log = isset($this->logmsg) ?  $this->logmsg : '';
//~
        //~ $this->response->body = $view->render();
    //~ }

	//~ public function action_del() {
//~
		//~ if( $this->permissions == $this::NONE_LEVEL ) {
			//~ $this->noperm();
			//~ return false;
		//~ }
//~
		//~ if ($this->request->method != 'POST')
			//~ return false;
//~
        //~ // удаляем зону
//~
		 //~ $params = $this->request->post();
//~
		 //~ $this->pixie->db->query('delete','itbase')
						 //~ ->table('names')
						 //~ ->where('id',$params['id'])
						 //~ ->execute();
//~
		 //~ $this->pixie->db->query('delete','itbase')
						 //~ ->table('records')
						 //~ ->where('domain_id',$params['id'])
						 //~ ->execute();
    //~ }

	public function action_single() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$view = $this->pixie->view('bserver_view');

		// вывод лога
		$view->log = $this->getVar($this->logmsg,'');

		// если не редактирование,т.е. начальный вход
		if( ! $this->request->param('id') )
			return; // "<img class='lb' src='/Dns.png' />";

		$this->_id = $this->getVar($this->_id, $this->request->param('id'));

		$view->records = $this->pixie->db->query('select','itbase')
										->table('records')
										->where('names_id',$this->_id)
										->execute();


		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

        $this->response->body = $view->render();
    }

	public function action_add() {

		if( $this->permissions != $this::WRITE_LEVEL ) {
			$this->noperm();
			return false;
		}

        if ($this->request->method == 'POST') {

			$params = $this->request->post();

			$entry = array( 'name' 	=> $params['name'],
							'pid' 	=> $params['pid']
					);
//print_r($entry); exit;

			if ( $params['id'] == 0 ) {
			// Новая запись
				$this->pixie->db->query('insert','itbase')
								->table('names')
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id('itbase');
			}
			elseif ( $this->getVar($params['stat'],0) == 2)	{
			// Удаляем запись
				$this->pixie->db->query('delete','itbase')
								->table('names')
								->where('id', $params['id'])
								->where('or',array('pid', $params['id']))
								->execute();

				//~ $this->pixie->db->query('delete','itbase')
								//~ ->table('records')
								//~ ->where('pid', params['id'])
								//~ ->execute();
			}
			else {
			// Редактирование
//print_r($params); exit;
				$this->pixie->db->query('update','itbase')
								->table('names')
								->data($entry)
								->where('id', $params['id'])
								->execute();
			}
//exit;
			//~ // Ошибки имели место
			//~ if( isset( $this->logmsg ) ) {
//~
				//~ if ( $params['domain_id'] ) {
					//~ // Ошибка во время редактирования
					//~ $this->_id = $params['domain_id'];
					//~ $this->action_single();
				//~ }
				//~ else
					//~ $this->action_new();
			//~ }
			//~ else
			$this->response->body = $params['id'];
		}

	}


 }

?>
