<?php
namespace App;

class ItBase extends Page {

	protected $view_tmpl;


	public function action_view() {

		// Проверка легитимности пользователя и его прав
        if( $this->permissions == $this::NONE_LEVEL )
			return  $this->noperm();

		$this->view->script_file = "<script type='text/javascript' src='/js/jquery.dynatree.min.js'></script>";
		$this->view->script_file .= "<script type='text/javascript' src='/js/tree_init.js'></script>";
		if( file_exists($_SERVER['DOCUMENT_ROOT'].'/js/'.$this->ctrl.'.js') ) {
			$this->view->script_file .= '<script type="text/javascript" src="/js/'.$this->ctrl.'.js"></script>';
		}

		$this->view->css_file = '<link rel="stylesheet" href="/css/skin/ui.dynatree.css" type="text/css" />';
		if( file_exists($_SERVER['DOCUMENT_ROOT'].'/css/'.$this->ctrl.'.css') ) {
			$this->view->css_file .= '<link rel="stylesheet" type="text/css" href="/css/'.$this->ctrl.'.css" />';
		}

		// Подключаем файл, с названием равным контроллеру
		$this->view->subview = $this->ctrl;

		$this->response->body = $this->view->render();
    }


	protected function RecursiveTree(&$rs,$parent) {

	    $data = array();

		if (!isset($rs[$parent])) return false;

		foreach ($rs[$parent] as $row) {

				$chidls = $this->RecursiveTree($rs,$row->id);

				$out = array("title"=>$row->name, "key" => $row->id);
				// $row->records пусто для разделов
				if( $chidls || !$row->records) {
					 $out["isFolder"] = true;
					 $out["children"] = $chidls;
				}
				array_push($data, $out);
		}

		return $data;
	}

	protected function action_getTree() {

		$tree = $rs = array();

		$typenow = $this->request->param('controller');

		$tree = $this->pixie->orm->get('names')
								->where('page', $this->getVar($typenow))
								->order_by('pid')
								//->order_by('name')
								->find_all();

		foreach ($tree as $row)	{
			$rs[$row->pid][] = $row;
		}

		$tree_struct = $this->RecursiveTree($rs,0);
		$this->response->body =  json_encode($tree_struct);

	}

	public function action_records() {

		if( ! $this->_id = $this->request->param('id') )
			return;

		$entry = $this->pixie->orm->get('names')->where('id',$this->_id)->find();

		$data = array();
		$rows = json_decode($entry->records);

		for($i=0; $i < count($rows); $i++) {

			$data[] = array($rows[$i]->fname,
							$rows[$i]->fval,
							"DT_RowClass" => "gradeA",
							'DT_RowId'=>"tab-rec-".$i);
		}

        $this->response->body = json_encode($data);
    }

	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');  // ID раздела
		$view 		= $this->pixie->view('form_'.$tab);
		$view->page	= $this->request->param('controller');
		$view->pid	= $this->request->post('pid');
		$view->tab  = $tab;

		$view->data = $this->pixie->orm->get('names')
								 ->where('id',$this->_id)
								 ->find();

        $this->response->body = $view->render();
    }

	//~ public function action_edit() {
//~
		//~ if( $this->permissions != $this::WRITE_LEVEL )
			//~ return $this->noperm();
//~
		//~ if( ! $params = $this->request->post() )
			//~ return;
//~
		//~ try {
			//~ $tab  = $params['tab'];
			//~
			//~ $params['pid'] = $params['in_root'] ? '0' : $params['pid'];
			//~ unset($params['tab'], $params['in_root']);
//~
			//~ $is_update = $params['id'] ? true : false;
//~
			//~ // сохраняем модель
			//~ // Если в запрос поместить true -  предполагается UPDATE
			//~ $row = $this->pixie->orm->get($tab)
									//~ ->values($params, $is_update)
									//~ ->save();
//~
			//~ $id = $params['id'];
			//~ unset( $params['id'] );
//~
			//~ $returnData  = array_values($params);
			//~ $returnData['DT_RowId']		= 'tab-'.$tab.'-'.($id ? $id : $row->id); // Если id = 0 - вынимаем новый id
//~
			//~ $this->response->body = json_encode($returnData);
		//~ }
		//~ catch (\Exception $e) {
			//~ $this->response->body = $e->getMessage();
			//~ return;
		//~ }
//~
//~
	//~ }

	public function action_delEntry() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() or
			! $this->_id = $this->request->param('id') )
			return;

		try {

			$this->pixie->orm->get($params['tab'])
							 ->where('id', $this->_id)
							 ->delete_all();
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }

}
