<?php

namespace App\Controller;

class Dns extends \App\Page {

	/*public function action_view() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->script_file	= '<script type="text/javascript" src="/js/dns.js"></script>';
		$this->view->entries 		= $this->pixie->orm->get('dns')->find_all();
		$this->view->subview 		= 'dns';

		$this->response->body	= $this->view->render();
	}*/

	public function action_record1s() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$entries = $this->pixie->orm->get('records')->where('domain_id',$this->_id)->find_all();


		$this->response->body = json_encode($entries);
    }

	public function action_getTree() {

		$entries = $this->pixie->db->query('select','dns')
									->fields($this->pixie->db->expr("
										D.id AS did, 
										D.name AS dname, 
										D.master,
										D.type AS dtype, 
										R.id, 
										R.name AS value, 
										R.type, 
										R.content, 
										R.ttl,
										IF(R.disabled=0,1,0) AS active,
										R.prio"))
									->table("domains",'D')
									->join( array('records','R'), array("D.id", "R.domain_id"))
									->execute()->as_array();
		$result = array();

        foreach( $entries as $entry) {
			
			$domain = array("id" => $entry->did, 
							"value" => $entry->dname, 
							"master" => $entry->master, 
							"type" => $entry->dtype, 
							"open" => true);
			
			$i   	= $entry->did;

            unset($entry->did, $entry->master, $entry->dtype);
            // unset($entry->did, $entry->dname, $entry->master, $entry->dtype);

            if( ! isset( $result[ $i ]["data"] ) ) {
                $result[ $i ] = $domain;
                $result[ $i ]["data"] = array();
            }

            if( $entry->id )
           		array_push( $result[ $i ]["data"], $entry );
        }

		$this->response->body = json_encode( array_values($result));
    }

	public function action_save() {

		
		if( ! $params = $this->request->post() )
			return;

		try {

			$is_update         = $params['is_new'] ? false : true;
			$params['name']    = $params['value'];
			$params['disabled'] = ! $params['active'];

			// print_r($params); exit;
			// Если в запрос поместить true -  предполагается UPDATE
			if( $params['$parent'] == 0 )
				$sql = $this->pixie->orm->get("dns");
			else
				$sql = $this->pixie->orm->get("dns")->records;
			
			unset($params['$parent'],$params['$level'],$params['$count'],$params['is_new'],$params['open'],$params['value'],$params['dname'], $params['active']);

			$sql->values($params, $is_update)->save();

		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
	}

	public function action_delEntry() {

		
		if( ! $params = $this->request->post() )
			return;

		try {
			if( $params['$parent'] == 0 ) 
				$this->pixie->orm->get("dns")->where( 'id', $params['id'])->delete_all();
			else
				$this->pixie->orm->get("dns")->records->where( 'id', $params['id'])->delete_all();

		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
    }

}
?>
