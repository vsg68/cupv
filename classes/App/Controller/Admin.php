<?php
/*

 */
namespace App\Controller;

class Admin extends \App\Page {



    public function action_view() {

 		$this->view->script_file	= '<script type="text/javascript" src="/js/admin.js"></script>';
		//$this->view->css_file 		= '<link rel="stylesheet" href="/admin.css" type="text/css" />';

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview = 'admin';

		$this->view->entries = $this->pixie->db->query('select','admin')
									->table('sections')
									->execute();


        $this->response->body	= $this->view->render();
    }

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$data 	 = array();
		$entries = $this->pixie->db->query('select','admin')
									->table('controllers')
									->where('section_id',$this->_id)
									->execute();

		foreach($entries as $entry)
			$data[] = array( $entry->name,
							 $entry->class,
							 $entry->active,
							 'DT_RowId' => 'tab-controllers-'.$entry->id,
							 'DT_RowClass' => 'gradeB'
							);

		$this->response->body = json_encode($data);

    }

 	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_admin');
		$view->pid	= $this->request->post('init');
		$view->tab  = $tab;

		$data = $this->pixie->db->query('select','admin')
										->table($tab)
										->where('id',$this->_id)
										->execute()
										->current();

		// Для дефолтных значений таблицы алиасов
		if( $tab == 'controllers' ) {

			$options = $this->getFreeControllers();

			// Добавим туда текущий контроллер
			if( $data ) {
				array_push( $options, $data->class);
			}

			$view->options = $options;
		}

	   $view->data = $data;
       $this->response->body = $view->render();
    }

	public function action_edit() {

		if( ! $params = $this->request->post() )
			return;

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		try {
			$returnData  = array();
			// Массив, который будем возвращать. Позиция важна
			$entry['name'] = $params['name'];

			if($params['tab'] == 'sections' )
				$entry['note'] = $this->getVar($params['note']);

			if($params['tab'] == 'controllers' ) {

				$entry['class'] = $params['class'];
				$entry['section_id'] = $params['section_id'];
			}

			$entry['active'] = $this->getVar($params['active'],0);


			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert','admin')
								->table($params['tab'])
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id();

			}
			else {
			// Существующая запись
				$this->pixie->db->query('update','admin')
								->table($params['tab'])
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}

		unset($entry['section_id']);

		$returnData 				= array_values($entry);
		$returnData['DT_RowId']		= 'tab-'.$params['tab'].'-'.$params['id'];
		$returnData['DT_RowClass']	= ($params['tab'] == 'controllers') ? 'gradeB': '';

		$this->response->body = json_encode($returnData);
	}

	public function action_delEntry() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		try {
			$this->pixie->db->query('delete','admin')
							->table($params['tab'])
							->where('id',$params['id'])
							->execute();


			// Если есть связанные страницы - обнуляем связь (section_id)
			if( $params['tab'] == 'sections' ) {

				$this->pixie->db->query('delete','admin')
								->table('controllers')
								->where('section_id',$params['id'])
								->execute();
			}
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}
    }

/*
 * Функция показывает общую таблицу
 * Важно, что название класса - уникально
 */
	public function action_showTable() {

		$entries = $this->pixie->db->query('select','admin')
									->fields(array('C.class', 'c_class'),
											 array('C.name', 'c_name'),
											 array('S.name','s_name'),
											 array('C.active', 'c_active'),
											 $this->pixie->db->expr('"gradeX" AS DT_RowClass'))
									->table('controllers','C')
									->join(array('sections','S'),array('S.id','C.section_id'))
									->order_by('S.name')
									->execute()
									->as_array();

		// Ищем, какие контроллеры еще остались не в базе
		$controllers = $this->get_ctrl();
		$data = array();
		foreach($entries as $entry)	{

			$data[] = array($entry->c_class,
							$entry->c_name,
							$entry->s_name,
							$entry->c_active,
							"DT_RowClass" => "gradeA"
							);

			unset($controllers[$entry->c_class]);
		}

		if(is_array($controllers)) {
			// Если остались незадействованные контроллеры - мы их добавляем в конец задействованных
			foreach($controllers as $k => $v) {
				$data[] = array($k,'','','',"DT_RowClass" => "gradeA");
			}
		}

		$retutnData = array("sEcho" => 1,
							"iTotalRecords" => sizeof($data) + sizeof($controllers),
							"iTotalDisplayRecords" => sizeof($data) + sizeof($controllers),
							"aaData" => $data
							);

		$this->response->body = json_encode($retutnData);
	}

/*
 * Берем еще свободные контроллеры
 */
	private function getFreeControllers() {

		$entries = $this->pixie->db->query('select','admin')
									->table('controllers')
									->execute()
									->as_array();

		// Ищем, какие контроллеры еще остались не в базе
		$controllers = $this->get_ctrl();

		foreach($entries as $entry)	{

			unset($controllers[$entry->class]);
		}

		return array_keys($controllers);

	}
}
?>
