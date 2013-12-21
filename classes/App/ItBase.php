<?php
namespace App;

class ItBase extends Page {

	/*
	 * Функция добавляет элементы массива, для правильной передачи
	 */
	protected function DTPropAddToAssocArray($row) {
		static $i;
		$row->DT_RowClass = 'gradeA';
		$row->DT_RowId = 'tab-rec-'.(isset($i) ? $i : 0);
		$i++;
		return $row;
	}

	/*
	 * Функция добавляет элементы массива, для правильной передачи
	 */
	protected function DTPropAddToArray($row) {
		static $i;
		$row['DT_RowClass'] = 'gradeB';
		$row['DT_RowId'] = 'tab-cont-'.(isset($i) ? $i : 0);
		$i++;
		return $row;
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

		//~ $entries = $this->pixie->orm->get('names')->find_all();
		//~ foreach($entries as $entry) {
			//~ $row = unserialize($entry->templ);
			//~ $entry->templ = json_encode($row);
			//~ $entry->save();
		//~ }
		//~ exit;


		$returnData = array();
		$rows = json_decode($entry->data);

		$returnData['aaData'] = array_map(array($this,'DTPropAddToAssocArray'), $rows->entry);

		if( isset($rows->records) ) {

			$returnData['records'] = array_map(array($this,'DTPropAddToArray'), $rows->records);
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

			if( count($rows->entry) && count($rows->records) ) {
				throw new \Exception("Сначала нужно удалить все данные объекта, а потом удалить сам объект. Кол-во записей: ".
										count($rows->entry)."; ".count($rows->records));
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

			if($params['tab'] == 'rec') {
				// delete item
				unset($rows->entry[$params['id']]);
			}

			if($params['tab'] == 'cont') {
				// delete item
				unset($rows->records[$params['id']]);
				$rows->records = array_values($rows->records);  // Иначе будет ассоциированный массив
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
/*** OLD ***/
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

}
