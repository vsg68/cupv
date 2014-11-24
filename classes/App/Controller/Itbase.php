<?php
namespace App\Controller;

class Itbase extends \App\Page {

	protected function RecursiveTree(&$rs, $parent) {

	    $data = array();

		if (!isset($rs[$parent])) return false;

		foreach ($rs[$parent] as $row) {

				$childs = $this->RecursiveTree($rs,$row->id);

				$out = (array)$row;

				if( isset($childs) && $childs ) {
					 $out["data"] = $childs;
				}

				array_push($data, $out);
		}

		return $data;
	}
	
	protected function action_getTree() {

		$rs = array();
		$tree = $this->pixie->db->query('select','itbase')
								->table("entries")
								->order_by('pid')
								->order_by('name')
								->execute()->as_array();               

		foreach ($tree as $row)	{
			$rs[$row->pid][] = $row;
		}

		$tree_struct = $this->RecursiveTree($rs,0);
		$this->response->body =  json_encode($tree_struct);

	}

	public function action_delEntry() {

		if( ! $params = $this->request->post() )
			return;

		try {
			$this->pixie->db->query("delete","itbase")
							->table("entries")
							->where('id',$params['id'])
							->execute();
			
			// Связанные документы //
			$this->pixie->db->query("delete","itbase")
							->table("records")
							->where('pid',$params['id'])
							->execute();

		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
    }

	
	/**
	 * выбирает из базы все записи, связвнные с PID, группируя по TID(тип записи), GID ("порядковый номер" типа записи)
	 * @return [json] [записи, которые будут формировать описание выделеннонго объекта]
	 */
	public function action_select() {

		if( ! $params = $this->request->get() )
			return;

		$data = array();
		
		$entry = $this->pixie->db->query('select','itbase')
								  ->table("strings")
								  ->where('pid',$params["pid"])
								  ->order_by("datatype")
								  ->execute()->as_array();
								  
		$this->response->body = json_encode($entry);
	}

	/**
	 * вынимаем записи для дерева объектов. 
	 * @return [json] [записи дерева объектов]
	 */
	public function action_RichSelect() {

		if( ! $params = $this->request->get() )
			return;

		$entry = $this->pixie->db->query('select','itbase')
								 ->fields( $this->pixie->db->expr("id, name AS value"))
								 // ->fields( $this->pixie->db->expr("id, name AS value,tsect"))
								 ->table('entries')
								 ->where('pid',$params["pid"])
								 ->where('tsect',$params["tsect"])
								 ->execute()->as_array();
		
		$this->response->body = json_encode($entry);
    }

    public function action_save() {

		if( ! $params = $this->request->post() )
			return false;

		try {
			$is_update = $params['is_new'] ? false : true;
            unset( $params['is_new'], $params['active'] );
			// Если в запрос поместить true -  предполагается UPDATE
			$this->pixie->orm->get("itbase")->strings->values($params, $is_update)->save();
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
    }

	public function action_savegroup() {

		if( ! $params = $this->request->post() )
			return false;

		try {
			$is_update = $params['is_new'] ? false : true;
			$copy_id   = isset( $params['copy_id'] ) ? $params['copy_id'] : false;
            unset( $params['is_new'], $params['$parent'], $params['$level'], $params['$count'], $params['value'], $params['open'], $params['copy_id'] );

			// Если в запрос поместить true -  предполагается UPDATE
			$this->pixie->orm->get("itbase")->values($params, $is_update)->save();

			if ( $copy_id ) {
				$entries = $this->pixie->db->query("select","itbase")
										->table("strings")
										->where('pid',$copy_id )
										->execute()->as_array();

				// Связанные документы 
				foreach($entries as $entry) {
					$entry        = (array)$entry;
					$entry["pid"] = $params["id"];
					unset($entry["id"],$entry["value"]);
					
					$this->pixie->db->query("insert","itbase")
									->table("strings")
									->data($entry)
									->execute();
				}
			}
		}
		
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
    }

    protected function action_str(){
    	exit;
    	$tree = $this->pixie->db->query('select','itbase')
								->table('records')
								->execute()->as_array();

		try{
			foreach ($tree as $row)	{

				$fields = json_decode($row->fields);
				
				foreach ($fields as $str){	
				echo $row->id."; ";		
					$data1 = array(
						'pid' => $row->pid,
						'datatype' => $row->datatype,
						'label' => $str->label,
						'value' => $str->name,
						'ftype' => isset($str->type) ? $str->type : "text"
						);
					$this->pixie->db->query('insert','itbase')->table('strings')->data($data1)->execute();
	    		}
	    	}
    		echo "that is all";
    	}
    	catch (\Exception $e) {
			echo $e->getMessage();
		}
    }	

	protected function action_getTree_old() {

		exit;
		$tree = $data = $arr = array();

		$tree = $this->pixie->db->query('select','itbase')
								->table('names')
								->order_by('pid')
								->execute()->as_array();

		foreach ($tree as $row)	{

			$data1 = array(
					'id' => $row->id,
					'pid' => $row->pid,
					'name' => $row->name,
					'tsect' => ($row->page == 'badm') ? 0 : 1,
					);

			//$this->pixie->db->query('insert','itbase')->table('entries')->data($data1)->execute();

			$arr = json_decode($row->data);
			
			

			if(isset($arr->entry)) {
				$fields = array();
				foreach( $arr->entry as $entry) {
		
					$data1 = array(
							'pid' => $row->id,
							'datatype' => 1, 
							"label"	=> $entry[0],
							"value" => $entry[2],
							"ftype" => $entry[1],

					$this->pixie->db->query('insert','itbase')->table('strings')->data($data1)->execute();
				}
			}	

			if(isset($arr->records)) {
				
				foreach( $arr->records as $records ) {
					$fields = array();
				 	
				 	if( ($records[0] == "Контакт") OR ( $records[0] == $records[1] && $records[0] == $records[2] && $records[0] == $records[3] ) ) 
				 		continue;


					foreach( $records as $k=>$v) {
						if($k == 0) $label = "Контакт";
						if($k == 1) $label = "Должность";
						if($k == 2) $label = "Телефон";
						if($k == 3) $label = "Email";

						$data1 = array('pid' => $row->id,
											'datatype' => 2, 
											"label"	=> $label,
											"value" => $v,
											"ftype" => "text",);
					}

					$this->pixie->db->query('insert','itbase')->table('strings')->data($data1)->execute();
				}
			}
		}

		 print_r ($arr);

		// $tree_struct = $this->RecursiveTree($rs);

		// $this->response->body =  json_encode($tree);
	}
		

}
