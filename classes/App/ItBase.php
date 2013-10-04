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
		$this->view->css_file .= '<link rel="stylesheet" href="/tree_init.css" type="text/css" />';


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

//		$typenow = $this->request->get('page');
		$typenow = $this->request->param('controller');

		$tree = $this->pixie->db->query('select','itbase')
								->table('names')
								->where('page', $this->getVar($typenow))
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

	public function action_add() {

		if( $this->permissions != $this::WRITE_LEVEL ) {
			$this->noperm();
			return false;
		}

        if ($this->request->method == 'POST') {

			$entry = $templ = array();
			$params = $this->request->post();

			if( isset($params['fname']) ) {
				foreach($params['fname'] as $key=>$val) {

					$templ['entry'][$key] = array('fname' => $params['fname'][$key],
												  'ftype' => $params['ftype'][$key],
												  'fval'  => $params['fval'][$key]
												  );
				}
			}

			if( isset($params['tdname']) ) {

				foreach($params['tdname'] as $key=>$tdvalues) {

					foreach($tdvalues as $tdvalue) {
						if( !isset($templ['records'][$key]) )
							$templ['records'][$key] = array();

						array_push($templ['records'][$key], $tdvalue);
					}
				}
			}

			// копирование шаблона
			if( isset($params['tmpl_id']) ) {

					$template = $this->pixie->db->query('select','itbase')
												->table('names')
												->where('id',$params['tmpl_id'])
												->execute()
												->current();

					$entry['templ'] = $template->templ;
			}

			// заполняем массив
			if( isset($params['name']) )	$entry['name'] = $params['name'];
			if( isset($params['pid']) )		$entry['pid']  = $params['pid'];
			if( count($templ) )				$entry['templ'] = serialize($templ);

			$entry['page'] = $this->request->param('controller');

//print_r($entry); exit;

			if ( $params['id'] == 0 ) {
			// Новая запись
				$this->pixie->db->query('insert','itbase')
								->table('names')
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id('itbase');

			}
			elseif ( $this->getVar($params['stat'],0) == 2)	{
			// Удаляем запись
				$this->pixie->db->query('delete','itbase')
								->table('names')
								->where('id', $params['id'])
								->where('or',array('pid', $params['id']))
								->execute();

			}
			else {
			// Редактирование
				$this->pixie->db->query('update','itbase')
								->table('names')
								->data($entry)
								->where('id', $params['id'])
								->execute();
			}

			$this->response->body = $params['id'];
		}

	}

	protected function getTemplItems() {

		$menu_ul = '';

		$menu_block = $this->pixie->db->query('select','itbase')
									 ->table('names')
									 ->where('page','btmpl')
									 ->execute()
									 ->as_array();


		if( is_array($menu_block) )	{

			$menu_ul .= '<ul>';

			foreach($menu_block as $item)
				$menu_ul .= '<li><span id="x-'. $item->id .'">'.$item->name.'</span></li>';

			$menu_ul .= '</ul>';
		}

		return $menu_ul;

	}
}
