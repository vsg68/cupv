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

        $this->response->body = $data ? json_encode($data) : '';
    }

	//~ public function action_showEditFormTree() {
//~
		//~ if( $this->permissions == $this::NONE_LEVEL )
			//~ return $this->noperm();
//~
		//~ if( ! $tab = $this->request->post('t') )
			//~ return;
//~
		//~ // при tab = rec ID и PID меняются местми
		//~ $this->_id 	= $this->request->param('id');
		//~ $view 		= $this->pixie->view('form_'.$tab);
		//~ $view->page	= $this->request->param('controller');
		//~ $view->pid	= $this->request->post('pid');
		//~ $view->tab  = $tab;
//~
		//~ $data = $this->pixie->orm->get('names')
								 //~ ->where('id',$this->_id)
								 //~ ->find();
//~
		//~ $view->data = $data;
//~
        //~ $this->response->body = $view->render();
    //~ }

	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $tab = $this->request->post('t') )
			return;

		// для правильного отображения меняем местами id и  pid
		$this->_id 	= ($tab == 'tree') ? $this->request->param('id') : $this->request->post('init');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;

		$view->page	= $this->request->param('controller');
		// Во втором случае пид - это ID записи
		$view->pid	= ($tab == 'tree') ? $this->request->post('pid') : $this->_id;

		$view->id = $this->request->param('id');

		$data = $this->pixie->orm->get('names')
								 ->where('id',$this->_id)
								 ->find();

		if( $tab == 'rec' ) {
			$data->records = isset($data->records) ? json_decode($data->records) : '';
		}

		$view->data = $data;

        $this->response->body = $view->render();
    }

	public function action_editTree() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {
			$tab  = isset($params['tab']) ? $params['tab'] : '';

			$params['pid']  = (isset($params['in_root']) && $params['in_root']) ? '0' : $params['pid'];
			$params['page'] = $this->request->param('controller');
			unset($params['tab'], $params['in_root']);

			$is_update = $params['id'] ? true : false;

			// сохраняем модель
			// Если в запрос поместить true -  предполагается UPDATE
			$row = $this->pixie->orm->get('names')
									->values($params, $is_update)
									->save();

			$id = $params['id'];
			unset( $params['id'] );

			// tab = '' - идет запрос на изменение принадлежности
			if( $tab ) {
				$returnData  = array('title' => $params['name'],
									 'isFolder' => true,
									 'key'   => ($id ? $id : $row->id));

				$this->response->body = json_encode($returnData);
			}
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}


	}


	public function action_delEntryTree() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() or
			! $this->_id = $this->request->param('id') )
			return;

		try {

			$data = $this->pixie->orm->get($params['tab'])
									 ->where('id', $this->_id)
									 ->find();
			// вынимаем данные
			$records = json_decode($data->records);

			if( count($records) ) {
				throw new \Exception("Сначала нужно удалить все данные объекта, а потом удалить сам объект. Кол-во записей: ".count($records));
			}
			else {
				$data->delete();
			}
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }

    public function action_delEntry() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {

			$row = $this->pixie->orm->get('names')
									->where('id', $params['pid'])
									->find();

			$records = json_decode($row->records);

			unset($records[$params['id']]);

			$row->records = json_encode($records);
			$row->save();



		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }

}
