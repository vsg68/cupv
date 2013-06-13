<?php
/*

 */
namespace App\Controller;

class Aliases extends \PHPixie\Controller {

    public function action_index() {

        $view = $this->pixie->view('main');
		$view->subview = 'aliases_view';

		$aliases_arr = array();

        $aliases = $this->pixie->db
								->query('select')
								->fields('alias_name','delivery_to')
								->table('aliases')
								->order_by('alias_name')
								->execute();

		//~ $view->domains = $this->pixie->db
								//~ ->query('select')
								//~ ->fields('domain_name')
								//~ ->table('domains')
								//~ ->where('delivery_to','virtual')
								//~ ->execute();

		foreach ($aliases as $alias) {

			if( ! isset($aliases_arr[$alias->alias_name]) )
				$aliases_arr[$alias->alias_name] = array();

			array_push( $aliases_arr[$alias->alias_name], $alias->delivery_to);
		}


		$view->aliases_arr = $aliases_arr;

        $this->response->body = $view->render();
    }
}
?>
