<?php

namespace App\Controller;

class Badm extends \App\ItBase {


    public function action_view1() {


		$this->view->script_file .= '<script type="text/javascript" src="/badm.js"></script>';

		$this->view->css_file 	 .= '<link rel="stylesheet" href="/badm.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$this->view->menu_block = $this->getTemplItems(); // Тут стоит меню шаблонов

		//$this->view->ed_block = $this->action_single();

        $this->response->body = $this->view->render();
    }

	public function action_single() {

		return $this->show_single( $this->pixie->view('badm_view') );

	}
 }

?>
