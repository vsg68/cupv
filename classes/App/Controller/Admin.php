<?php
/*

 */
namespace App\Controller;

class Admin extends \App\Page {



    public function action_view() {

 		$this->view->script_file	= '<script type="text/javascript" src="/js/admin.js"></script>';
 		$this->view->script_file	.= '<script type="text/javascript" src="/js/rowReordering.js"></script>';
		//$this->view->css_file 		= '<link rel="stylesheet" href="/admin.css" type="text/css" />';

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview = 'admin';

		$this->view->entries = $this->pixie->orm->get('section')->find_all();

        $this->response->body	= $this->view->render();
    }

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$data 	 = array();
		$entries = $this->pixie->orm->get('section')->where('id',$this->_id)->find();

		// json controllers array
		$controllers = ($entries->ctrls) ? unseriaize($entries->ctrls) : array();

//print_r($entries); exit;
		foreach($controllers as $entry)
			$data[] = array( $entry['order'],
							 $entry['name'],
							 $entry['class'],
							 $entry['active'],
							 'DT_RowId' => 'tab-controllers-'.$entry['id'],
							 'DT_RowClass' => 'gradeB'
							);

		$this->response->body = json_encode($data);

    }

 	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');  // ID раздела
		$view 		= $this->pixie->view('form_admin');
		$ord		= $this->request->post('init'); //
		$view->tab  = $tab;

		$data = $this->pixie->orm->get( 'section' )->where('id',$this->_id)->find()->as_array();

		// Для дефолтных значений таблицы алиасов
		if( $tab == 'controllers' ) {

			$options 	 = $this->getFreeControllers();
			$controllers = $data->ctrls ? unserialize($data->ctrls) : array();

			// Добавим туда текущий контроллер
			if( $data ) {
				array_push( $options, $data[$ord]['name']);
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
			/*
			 * 1) Сначала выбираем список контроллеров нужного раздела
			 * 2) Редактируем
			 * 3) Сохраняем
			 *
			 */
			$returnData  = array();

			// 1)
			$data = $this->pixie->orm->get('section')->where('id',$this->_id)->find();

			// 2)
			$newdata = array('name'   => $params['name'],
							 'note'   => $this->getVar($params['note']),
							 'active' => $this->getVar($params['active'],0));

			if($params['tab'] == 'sections' ) {
				$data->name   = $newdata['name'];
				$data->note   = $newdata['note'];
				$data->active = $newdata['active'];
			}
			elseif ($params['tab'] == 'controllers' ) {

				$controllers = ($data->ctrls) ? unserialize($data->ctrls) : array();

				// Если есть такой массив - редактируем, нет - добавляем
				if ( $controllers[$params['ord']] ) {
					$controllers[$params['ord']] = $newdata;
				}
				else {
					array_push($controllers, $newdata);
				}

				$data->ctrls = serialize($controllers);
			}

			// 3)
			$data->save();

			// Отсылаем ответ
			$returnData 				= array_values($newdata);
			$returnData['DT_RowId']		= 'tab-'.$params['tab'].'-'.$params['id'];
			$returnData['DT_RowClass']	= ($params['tab'] == 'controllers') ? 'gradeB': '';

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}
	}

	public function action_delEntry() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		try {
			if( $params['tab'] == 'sections' ) {
				$this->pixie->orm->get('section')->where('id',$this->_id)->delete();
			}
			elseif( $params['tab'] == 'controllers' ) {

				$data = $this->pixie->orm->get('section')->where('id',$this->_id)->find();
				$controllers = unserialize($data->ctrls);

				unset($controllers[$ord]);

				$data->ctrls = serialize($controllers);
				$data->save();
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

		$entries = $this->pixie->db->query('select')
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
	private function getFreeControllers($id) {

		$entries = $this->pixie->orm->get('section')->where('id',$id)->find()->as_array();

		// Ищем, какие контроллеры еще остались не в базе
		$controllers = $this->get_ctrl();

		foreach($entries as $entry)	{

			unset($controllers[$entry['name']]);
		}

		return array_keys($controllers);

	}
}
?>
