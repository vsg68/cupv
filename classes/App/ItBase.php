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
								->execute()
								->as_array();

		foreach ($tree as $row)	{

			$rs[$row->pid][] = $row;
		}

		$tree_struct = str_replace('},]', '}]', '['. $this->RecursiveTree($rs,0) .']') ;
//$tree_struct = '[{"title": "Item3333 1"},{"title": "Sub-item 2.1"},{"title": "Sub-item 2.2"}]';
//$tree_struct = '[{"title": "aaaaa"},{"title": "bbbb", "isFolder":"true", "key":"folder2", "children": [{"title":"dddd", "isFolder":"true", "key":"folder2", "children": [{"title":"ffff"},{"title":"eeee"}]},{"title":"cccc"}]}]';
		$this->response->body =  $tree_struct;

	}
}
