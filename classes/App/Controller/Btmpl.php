<?php

namespace App\Controller;

class Btmpl extends \App\ItBase {



    public function action_view() {

		//$this->type = 'serv';

		$this->view->script_file .= '<script type="text/javascript" src="/btmpl.js"></script>';

		$this->view->css_file 	 .= '<link rel="stylesheet" href="/btmpl.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}
//		$this->view->subview = 'base_main';

		$this->view->menu_block = '';


		$this->view->ed_block = $this->action_single();

        $this->response->body = $this->view->render();
    }



	public function action_single() {

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$view = $this->pixie->view('btmpl_new');

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

//print_r($view->templ['records']);exit;
		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

        $this->response->body = $view->render();
    }



 }

?>
