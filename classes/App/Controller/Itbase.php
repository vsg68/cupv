<?php
namespace App\Controller;

class Itbase extends \App\Page {

	/*
	 * Функция добавляет элементы массива, для правильной передачи
	 */
	protected function DTPropAddToEntry($row,$tab,$class) {

		if(! count($row)) {	return false;	}

		$row['DT_RowClass'] = $class;
		$row['DT_RowId'] = 'tab-'.$tab;
		return array_map('nl2br', $row);
	}

	/*
	 * Функция добавляет элементы массива, для правильной передачи
	 */
	protected function DTPropAddToArray($row,$tab,$class) {

		$arr = array();

		if( count($row) ) {
			foreach( $row as $k => $val ) {
					$val['DT_RowClass'] = $class;
					$val['DT_RowId'] = 'tab-'.$tab.'-'.$k;
					$arr[] = array_map('nl2br', $val);
			}
		}

		return $arr;
	}


	protected function RecursiveTree(&$rs, $parent) {

	    $data = array();

		if (!isset($rs[$parent])) return false;

		foreach ($rs[$parent] as $row) {

				$childs = $this->RecursiveTree($rs,$row->id);

				$out = array("name"=>$row->name, "id" => $row->id);

				if( isset($childs) && $childs ) {
					 $out["data"] = $childs;
					 // echo 'a,';
				}

				array_push($data, $out);
		}

		return $data;
	}
	// protected function RecursiveTree(&$rs,$parent) {

	//     $data = array();

	// 	if (!isset($rs[$parent])) return false;

	// 	foreach ($rs[$parent] as $row) {

	// 			$chidls = $this->RecursiveTree($rs,$row->id);

	// 			$out = array("title"=>$row->name, "key" => $row->id);
	// 			// $row->records пусто для разделов
	// 			if( $chidls || !$row->data) {
	// 				 $out["isFolder"] = true;
	// 				 $out["children"] = $chidls;
	// 			}

	// 			array_push($data, $out);
	// 	}

	// 	return $data;
	// }

	protected function action_getTree() {

		$rs = array();
		$tree = $this->pixie->orm->get('itbase')
								->where('tpage', "")
								->order_by('pid')
								->find_all()->as_array(true);               

		foreach ($tree as $row)	{
			$rs[$row->pid][] = $row;
		}

		$tree_struct = $this->RecursiveTree($rs,0);

		$this->response->body =  json_encode($tree_struct);

	}

	protected function action_getTree_old() {

		exit;
		$tree = $data = array();

		$tree = $this->pixie->db->query('select','itbase')
								->table('names')
								->order_by('pid')
								->execute()->as_array();

		foreach ($tree as $row)	{

			$data1 = array(
					'id' => $row->id,
					'pid' => $row->pid,
					'name' => $row->name,
					'tpage' => ($row->page == 'badm') ? 0 : 1,
					);

			$this->pixie->db->query('insert','itbase')->table('docs')->data($data1)->execute();

			$arr = json_decode($row->data);
			
			if(isset($arr->entry)) {
				
				foreach( $arr->entry as $entry) {
					$data1 = array(
									'pid' => $row->id,
									'tpage' => 'list',
									'label' => $entry[0],
									'name' => $entry[2]
									);

					$this->pixie->db->query('insert','itbase')->table('docs')->data($data1)->execute();
				}
			}	
			
			if(isset($arr->records)) {
				foreach( $arr->records as $records ) {

				 	foreach( $records as $k => $val ) {
						if ($k == 0 ) $label = 'Контакт';
						if ($k == 1 ) $label = 'Должность';
						if ($k == 2 ) $label = 'Телефон';
						if ($k == 3 ) $label = 'Email';
	 
						$data1 = array(
										'pid' => $row->id,
										'tpage' => 'tabl',
										'label' => $label,
										'name' => $val
										);

						$this->pixie->db->query('insert','itbase')->table('docs')->data($data1)->execute();
					}
				}
			}
		}

		 print_r ($arr);

		// $tree_struct = $this->RecursiveTree($rs);

		// $this->response->body =  json_encode($tree);
exit;
	}

	public function action_select() {

		if( ! $params = $this->request->get() )
			return;

		$entry = $this->pixie->orm->get('itbase')
								  ->where('pid',$params["pid"])
								// ->where('tpage',$params["tpage"])
								  ->find_all()->as_array(true);
		
		$this->response->body = json_encode($entry);
	}

	public function action_getSelect() {


		if( ! $params = $this->request->get() )
			return;

		$entry = $this->pixie->db->query('select','itbase')
								 ->fields( $this->pixie->db->expr("id, name AS value "))
								 ->table('docs')
								 ->where('pid',$params["pid"])
								// ->where('tpage',$params["tsect"])    надо разделить по разделам
								 ->execute()->as_array();
		
		$this->response->body = json_encode($entry);
    }

	public function action_savegroup() {

		if( ! $params = $this->request->post() )
			return false;

		try {
			$is_update = $params['is_new'] ? false : true;
            unset( $params['is_new'], $params['$parent'], $params['$level'], $params['$count'], $params['value'] );

			// Если в запрос поместить true -  предполагается UPDATE
			$this->pixie->orm->get("itbase")->values($params, $is_update)->save();

		}
		
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
		
    }

	

}
