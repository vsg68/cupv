<?php
namespace App;

class ItBase extends Page {

	protected function RecursiveTree(&$rs,$parent) {

	    $out = '';

		if (!isset($rs[$parent])) return false;

		foreach ($rs[$parent] as $row) {

				$chidls = $this->RecursiveTree($rs,$row->id);

				//$prn_child = ($chidls) ? ', "isFolder":"true", "key":"folder2", "children": ['.$chidls.']' : '';
				$prn_child = ($chidls) ? ', "children": ['.$chidls.']' : '';

				$out .= '{"title":"'.$row->name. '", "key":"'.$row->id.'"' . $prn_child .'},';
		}

		return $out;
	}

	protected function action_getTree() {

		$tree = $rs = array();

		$tree = $this->pixie->db->query('select','itbase')
								->table('names')
								->order_by('pid')
								->order_by('name')
								->execute()
								->as_array();

		foreach ($tree as $row)	{

			$rs[$row->pid][] = $row;
		}

		$tree_struct = str_replace('},]', '}]', '['. $this->RecursiveTree($rs,0) .']') ;

		$this->response->body =  $tree_struct;

	}
}
