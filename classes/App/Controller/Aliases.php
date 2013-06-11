<?php
/*

 */
namespace App\Controller;

class Aliases extends \PHPixie\Controller {

    public function action_index() {

        $view = $this->pixie->view('main');
		$view->subview = 'aliases_view';

        $view->aliases = $this->pixie->db
								->query('select')
								->fields('alias_name','delivery_to')
								->table('aliases')
								->execute();

		$view->domains = $this->pixie->db
								->query('select')
								->fields('domain_name')
								->table('domains')
								->where('delivery_to','virtual')
								->execute();

        $this->response->body = $view->render();
    }
}
?>
