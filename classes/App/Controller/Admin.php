<?php
/*

 */
namespace App\Controller;

class Admin extends \App\Page {



    public function action_view() {

 		$this->view->script_file = '<script type="text/javascript" src="/js/admin.js"></script>';
		$this->view->script_file .= '<script type="text/javascript" src="/js/rowReordering.min.js"></script>';

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview = 'admin';
		$this->view->entries = $this->pixie->orm->get('sections')->find_all();


        $this->response->body = $this->view->render();

    }

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$data 	 = array();
		$entries = $this->pixie->orm->get('sections')->controllers
									->where('sections.id',$this->_id)
									->find_all();
		//~ $entries = $this->pixie->orm->get('controllers')
									//~ ->where('section_id',$this->_id)
									//~ ->find_all();

		foreach($entries as $entry) {
			$data[] = array( $entry->order,
							 $entry->name,
							 $entry->class,
							 $entry->active,
							 'DT_RowId' => 'tab-controllers-'.$entry->id,
							 'DT_RowClass' => 'gradeB'
							);
		}
		$this->response->body = json_encode($data);

    }

 	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');  // ID раздела
		$view 		= $this->pixie->view('form_admin');
		$view->pid	= $this->request->post('init'); //
		$view->tab  = $tab;

		$data = $this->pixie->orm->get($tab)
								 ->where('id',$this->_id)
								 ->find();

		if( $tab == 'controllers' ) {

			$options 	 = $this->getFreeControllers();

			// Добавим туда текущий контроллер
			if( $data->loaded() ) {
				array_push( $options, $data->class);
			}

			$view->options  = $options;

			// Смотрим порядковый номер
			if( $this->_id == 0 ) {
				$view->count = $this->pixie->orm->get('sections')
												->where('id',$view->pid)
												->controllers
												->count_all();
			}
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
			/*
			 * 1) Сначала выбираем список контроллеров нужного раздела
			 * 2) Редактируем
			 * 3) Сохраняем
			 *
			 */
			$returnData  = array();

			$params['active'] = $this->getVar($params['active'],0);

			$tab = $params['tab'];
			unset($params['tab']);

			// сохраняем модель
			// Если в запрос поместить true -  предполагается UPDATE
			$row = $this->pixie->orm->get($tab)
									->values($params, ($params['id'] ? true : false))
									->save();

			$id = $params['id'];
			unset( $params['section_id'], $params['id'] );

			// отдаем
			$returnData 				= array_values($params);
			$returnData['DT_RowClass']  = ($tab == 'controllers') ? 'gradeB' : '';
			$returnData['DT_RowId']		= 'tab-'.$tab.'-'.($id ? $id : $row->id); // Если id = 0 - вынимаем новый id

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
	}

	public function action_Reorder() {
		if( ! $params = $this->request->post() )
			return;

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		$tab = explode('-',$params['id'])[1];
		$id  = explode('-',$params['id'])[2];

		$lowEgde = ($params['direction'] == 'forward') ? $params['fromPosition'] : $params['toPosition'];
		$topEdge = ($params['direction'] == 'forward') ? $params['toPosition'] : $params['fromPosition'];

		try {
			$entries = $this->pixie->orm->get($tab)  	// controllers
										->where('id', $id)
										->sections  	// находим, какой у контроллера раздел
										->controllers   // находим контроллеры в этом разделе
										->where('order', '<=', $topEdge)
										->where('order', '>=', $lowEgde)
										->order_by('order')
										->find_all();

			foreach($entries as $entry) {
				if( $entry->order == $params['fromPosition']) {
					// Присваиваем полученный порядковый номер
					$entry->order = $params['toPosition'];
				}
				else {
					// В зависимости от направления мы либо увеличиваем,либо уменьшаем порядковый номер
					$entry->order = $entry->order + ($params['direction'] == 'forward' ? (-1) : 1);
				}

				$entry->save();
			}
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
	}

	public function action_delEntry() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		try {
			// delete_all() -- убиваем не доставая
			$this->pixie->orm->get($params['tab'])->where( 'id', $params['id'])->delete_all();

			// Если есть связанные страницы - обнуляем связь (section_id)
			if( $params['tab'] == 'sections' ) {
				$e = $this->pixie->orm->get('controllers')->where('section_id', $params['id']);  // обозначили связь
				$e->rights->delete_all();  // убили первую взаимосвязь
				$e->delete_all();			// убили саму таблицу
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

		$controllers = $this->get_ctrl();
		/*
		 * Готовим запрос для последующего вывода.
		 * Основное - это новое описание таблицы controllers.
		 * Выбираем ненулевые значения разделов.
		 */
		$entries = $this->pixie->orm->get('sections')->with('ctrls')
									->where($this->pixie->db->expr('COALESCE(ctrls.name, 0)'),'<>','0')
									->find_all();

		$data = array();
		foreach($entries as $entry)	{

			$data[] = array($entry->ctrls->class,
							$entry->ctrls->name,
							$entry->name,
							$entry->ctrls->active,
							"DT_RowClass" => "gradeA"
							);
			// Ищем, какие контроллеры еще остались не в базе
			unset($controllers[$entry->ctrls->class]);
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

		$entries = $this->pixie->orm->get('controllers')->find_all();

		// Ищем, какие контроллеры еще остались не в базе
		$controllers = $this->get_ctrl();

		foreach($entries as $entry)	{

			unset($controllers[$entry->class]);
		}

		return array_keys($controllers);

	}
}
?>
