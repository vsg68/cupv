<?php
namespace App;

class ItBase extends Page {

	protected $type;

	public function before() {

		$this->view = $this->pixie->view('main');
		$this->auth = $this->pixie->auth;

		$this->view->subview = 'base_main';

		$this->view->script_file  = '<script type="text/javascript" src="/jquery-ui.custom.min.js"></script>';
		$this->view->script_file .= '<script type="text/javascript" src="/jquery.dynatree.js"></script>';
		$this->view->script_file .= '<script type="text/javascript" src="/tree_init.js"></script>';

		$this->view->css_file = '<link rel="stylesheet" href="/skin/ui.dynatree.css" type="text/css" />';
		$this->view->css_file .= '<link rel="stylesheet" href="/badm.css" type="text/css" />';


		/* Определяем все контроллеры с одинаковыми ID */
		$this->view->menuitems = $this->pixie->db
										->query('select')
										->fields('Y.*')
										->table('controllers','X')
										->join(array('controllers','Y'),array('Y.section_id','X.section_id'),'LEFT')
										->where('X.class',strtolower($this->request->param('controller')))
										->where('Y.active',1)
										->order_by('Y.arrange')
										->execute();

		// Проверка легитимности пользователя и его прав
        if( $this->request->param('controller') != 'login' )
			$this->permissions = $this->is_approve();

	}

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

		$typenow = $this->request->get('page');

		$tree = $this->pixie->db->query('select','itbase')
								->table('names')
								->where('type', $this->getVar($typenow))
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
