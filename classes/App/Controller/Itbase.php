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
							->table("strings")
							->where('pid',$params['id'])
							->execute();

		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
    }

	public function action_delStr() {

		$destination = realpath('./files');

		if( ! $params = $this->request->post() )
			return;

		try {
			if( $params["datatype"] == "1") {
				$fileinfo = json_decode($params["value"],true);
				@unlink($destination."/".$fileinfo["sname"]);
			}

			$this->pixie->db->query("delete","itbase")
							->table("strings")
							->where('id',$params['id'])
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

		$entry = $this->pixie->db->query('select','itbase')
								  ->table("strings")
								  ->where('pid',$params["pid"])
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
								 ->table('entries')
								 ->where('fldr',1)
								 ->where('tsect',$params["tsect"])
								 ->execute()->as_array();
		
		$this->response->body = json_encode($entry);
    }
    public function action_save() {

		if( ! $params = $this->request->post() )
			return false;

		try {

			$is_update = $params['is_new'] ? false : true;

            unset( $params['is_new'], $params['files'] );  

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
	
	public function action_upload() {
		ini_set('max_execution_time', 120);
		$size = 256*256;
		$destination = realpath('./files');

			if (isset($_FILES['upload'])){
				$file        = $_FILES['upload'];
				$tmp 		 = array_reverse(explode(".", $file["name"]));
				$sname       = md5($file["name"]).".". $tmp[0];
				$filename    = $destination."/".$sname;

				// $filename = $destination."/".preg_replace("|[\\\/]|", "", $file["name"]);
				//check that file name is valid
				if ($filename != "" && !file_exists($filename)) {
					move_uploaded_file($file["tmp_name"], $filename);
					echo "{ status: 'server', sname:'$sname'}";
				// value print in base	
				} else 
					echo "{ status:'error' }";
			}
	

	}
}
