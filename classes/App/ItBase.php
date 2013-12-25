<?php
namespace App;

class ItBase extends Page {

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
		$this->view->subview = 'badm';
		$this->view->ctrl = $this->ctrl;

		$this->response->body = $this->view->render();
    }

	protected function RecursiveTree(&$rs,$parent) {

	    $data = array();

		if (!isset($rs[$parent])) return false;

		foreach ($rs[$parent] as $row) {

				$chidls = $this->RecursiveTree($rs,$row->id);

				$out = array("title"=>$row->name, "key" => $row->id);
				// $row->records пусто для разделов
				if( $chidls || !$row->data) {
					 $out["isFolder"] = true;
					 $out["children"] = $chidls;
				}

				array_push($data, $out);
		}

		return $data;
	}

	protected function action_getTree() {

		$tree = $rs = array();

		$tree = $this->pixie->orm->get('names')
								->where('page', $this->ctrl)
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

		// Начальный раздербан
		//~ $entries = $this->pixie->orm->get('names')->find_all();
		//~ foreach($entries as $entry) {
			//~ $row = unserialize($entry->templ);
			//~ $entry->templ = json_encode($row);
			//~ $entry->save();
		//~ }
		//~ exit;

		//~ // новые веяния - делаем данные, как массив
		//~ $entries = $this->pixie->orm->get('names')->find_all();
		//~ foreach($entries as $entry) {
//~ //print_r($entry->data); continue;
			//~ $row = json_decode($entry->data);
//~ //print_r($entry->data); continue;
			//~ $tmp = array();
			//~ if( !isset($row->entry))
				//~ continue;
//~ //echo $entry->name;
			//~ foreach($row->entry as $onerow) {
//~
				//~ if(is_array($onerow)) {
					//~ $tmp[] = $onerow;
				//~ }
				//~ else {
					//~ $tmp[] = array($onerow->fname,$onerow->ftype,$onerow->fval);
				//~ }
			//~ }
			//~ $row->entry = $tmp;
//~ //print_r($tmp);
			//~ $entry->data = json_encode($row);
			//~ $entry->save();
		//~ }
		//~ exit;

		$returnData = array();
		$rows = json_decode($entry->data);

		$returnData['aaData'] = $this->DTPropAddToArray($rows->entry, 'rec', 'gradeA');


		if( isset($rows->records) ) {

			$returnData['records'] = $this->DTPropAddToArray($rows->records, 'cont', 'gradeB');
		}

        $this->response->body = json_encode($returnData);
    }


	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $tab = $this->request->post('t') )
			return;

		// для правильного отображения меняем местами id и  pid
		$this->_id 	= ($tab == 'tree') ? $this->request->param('id') : $this->request->post('init');
		$view 		= ($tab == 'tree') ? $this->pixie->view('form_tree') : $this->pixie->view('form_rec');
		$view->tab  = $tab;

		$view->page	= $this->ctrl;
		// Во втором случае пид - это ID записи
		$view->pid	= ($tab == 'tree') ? $this->request->post('pid') : $this->_id;

		$view->id = $this->request->param('id');

		$entry = $this->pixie->orm->get('names')
								 ->where('id',$this->_id)
								 ->find();

		if( $tab != 'tree' ) {
			$entry = isset($entry->data) ? json_decode($entry->data) : '';
		}

		// Перед выводом - обрабатываем специальные символы
		array_walk_recursive($entry->entry, array($this,'blockspechars'));

		if( isset($entry->records) ) {
			array_walk_recursive($entry->records, array($this,'blockspechars'));
		}

		$view->data = $entry;

        $this->response->body = $view->render();
    }

	public function action_showNewForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$view = $this->pixie->view('form_'.$this->ctrl);

		if( ! $view->pid = $this->request->param('id') )
			return;

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
			$params['page'] = $this->ctrl;
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

			$entry = $this->pixie->orm->get($params['tab'])
									 ->where('id', $this->_id)
									 ->find();
			// вынимаем данные
			$rows = json_decode($entry->data);

			if( count($rows->entry) ) {

				$text = "Сначала нужно удалить все данные объекта, а потом удалить сам объект. Данные(кол-во записей):".count($rows->entry);

				if ( isset($rows->records) && count($rows->records) ) {
					$text .= ";  Контакты(кол-во записей); ".count($rows->records);
				}
				throw new \Exception( $text );
			}
			else {
				$entry->delete();
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
			$entry = $this->pixie->orm->get('names')
									->where('id', $params['pid'])
									->find();

			$rows = json_decode($entry->data);

			$data = ($params['tab'] == 'rec') ? $rows->entry : $rows->records;

			// delete item
			unset($data[$params['id']]);
			$data = array_values($data);  // Иначе будет ассоциированный массив

			if($params['tab'] == 'rec') {
				$rows->entry = $data;
			}
			else {
				$rows->records = $data;
			}

			$entry->data = json_encode($rows);
			$entry->save();
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }

   	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {
			$row = $this->pixie->orm->get('names')
									->where('id', $params['pid'])
									->find();

			$records = json_decode($row->data);

			$data = ( $params['tab'] == 'rec' ) ? $records->entry : $records->records;

			// Если новая запись - порядковый номер делаем руками
			$ord = ($params['id'] != '_0') ? $params['id'] : count($data) ;

			//$data[$ord] = array_map('htmlspecialchars', $params['fval']);
			$data[$ord] = $params['fval'];

			if( $params['tab'] == 'rec' ) {
				$records->entry = $data;
				$class = 'gradeA';
			}
			else {
				$records->records = $data;
				$class = 'gradeB';
			}

			$row->data = json_encode($records);
			$row->save();

			$returnData  = $this->DTPropAddToEntry($params['fval'], $params['tab'].'-'.$ord, $class);

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {

			$this->response->body = $e->getMessage();
			return;
		}
	}

	public function action_addNewItem() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return;

		try {
			$returnData = array();

			$records['entry'] = array_map(null, $params['fname'], $params['ftype'], $params['fval']);


			if( $this->ctrl == 'bcont' ) {
				$records['records'] = array();
			}

			$data = array('data' => json_encode($records),
						  'pid' 	=> $params['pid'],
						  'name' 	=> $params['fval'][0], //NAME
						  'page' 	=> $this->ctrl);

			$row = $this->pixie->orm->get('names')
									 ->values($data)
									 ->save();

			$returnData	= array('title' => $data['name'],
								'key' 	=> $row->id);

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}
	}

}
