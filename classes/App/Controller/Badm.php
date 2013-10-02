<?php

namespace App\Controller;

class Badm extends \App\ItBase {


    public function action_view() {


		$this->view->script_file .= '<script type="text/javascript" src="/badm.js"></script>';

		$this->view->css_file 	 .= '<link rel="stylesheet" href="/badm.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$this->view->menu_block = $this->getTemplItems(); // Тут стоит меню шаблонов

		$this->view->ed_block = $this->action_single();

        $this->response->body = $this->view->render();
    }


	public function action_single() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$view = $this->pixie->view('badm_view');

		// вывод лога
		$view->log = $this->getVar($this->logmsg,'');

		// если не редактирование,т.е. начальный вход
				if( ! $this->request->param('id') )
			return; // "<img class='lb' src='/Dns.png' />";

		$this->_id = $this->getVar($this->_id, $this->request->param('id'));


		$view->entries = $this->pixie->db->query('select','itbase')
										->table('names')
										->where('id',$this->_id)
										->execute()
										->current();

		$view->templ = ($view->entries->templ) ? unserialize($view->entries->templ) : array();

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

			$entry = $templ = array();
			$params = $this->request->post();

			if( isset($params['fname']) ) {
				foreach($params['fname'] as $key=>$val) {

					$templ['entry'][$key] = array('fname' => $params['fname'][$key], 'ftype' => $params['ftype'][$key]);
				}
			}

			if( isset($params['tdname']) ) {
				foreach($params['tdname'] as $key=>$val) {

					$templ['records'][] = $params['tdname'][$key];
				}
			}

			// копирование шаблона
			if( isset($params['tmpl_id']) ) {

					$template = $this->pixie->db->query('select','itbase')
												->table('names')
												->where('id',$params['tmpl_id'])
												->execute()
												->current();

					$entry['templ'] = $template->templ;
			}

			// заполняем массив
			if( isset($params['name']) )	$entry['name'] = $params['name'];
			if( isset($params['pid']) )		$entry['pid']  = $params['pid'];
			if( count($templ) )				$entry['templ'] = serialize($templ);

			$entry['page'] = $this->request->param('controller');

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

			$this->response->body = $params['id'];
		}

	}


 }

?>
