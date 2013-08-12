<?php

namespace App\Controller;

class Backup extends \App\Page {

    public function action_view() {


		$this->view->subview 		= 'backup_main';

		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$this->view->script_file	= '<script type="text/javascript">window.location = "http://backuppc.gmp.ru/cgi-bin/BackupPC_Admin"</script>';
		$this->view->css_file 		= '';

	    $this->response->body = $this->view->render();
    }

}
?>
