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

		$this->view->menu_block = '';

		$this->view->ed_block = $this->action_single();

        $this->response->body = $this->view->render();
    }

	public function action_single() {

		return $this->show_single( $this->pixie->view('btmpl_view') );

	}




 }

?>
