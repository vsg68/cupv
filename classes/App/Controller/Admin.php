<?php
/*

 */
namespace App\Controller;

class Admin extends \App\Page {


	 /* получаем название имеющихся контроллеров */
	private function get_ctrl() {

		$file_arr = Array();
		foreach( glob(dirname(__FILE__).'/*.php') as $name ) {

			preg_match('/([^\/]+)\.php$/',$name, $matches);
			if( isset($matches[1]) )
				array_push($file_arr, strtolower($matches[1]));
		}

		return $file_arr;
	}

    public function action_view() {

 		$this->view->script_file	= '<script type="text/javascript" src="/js/admin.js"></script>';
		//$this->view->css_file 		= '<link rel="stylesheet" href="/admin.css" type="text/css" />';

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview = 'admin';

		$this->view->entries = $this->pixie->db->query('select')
									->fields(array('S.id','s_id'),
											 array('S.name','s_name'),
											 array('S.note','s_note'),
											 array('S.active', 's_active'),
											 array('C.id', 'c_id'),
											 array('C.name', 'c_name'),
											 array('C.class', 'c_class'),
											 array('C.active', 'c_active'))
									->table('sections','S')
									->join(array('controllers','C'),array('S.id','C.section_id'))
									->order_by('S.name')
									->execute()
									->as_array();

        $this->response->body	= $this->view->render();
    }

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		$data 	 = array();
		$entries = $this->pixie->db->query('select')
									->table('controllers')
									->where('section_id',$this->_id)
									->execute();

		foreach($entries as $entry)
			$data[] = array( $entry->name,
							 $entry->class,
							 $entry->active,
							 'DT_RowId' => 'tab-controllers-'.$entry->id,
							 'DT_RowClass' => 'gradeX'
							);

		if( ! $data )
		  $data = array('','','');

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

		$view->data = $this->pixie->db->query('select')
										->table($tab)
										->where('id',$this->_id)
										->execute()
										->current();

		// Для дефолтных значений таблицы алиасов
		if( $tab == 'controllers' ) {
			$view->options = $this->get_ctrl();
		}

       $this->response->body = $view->render();
    }

	public function action_edit() {

		if( ! $params = $this->request->post() )
			return;

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		// Массив, который будем возвращать. Позиция важна
		$entry['name'] = $params['name'];

		if($params['tab'] == 'sections' )
			$entry['note'] = $this->getVar($params['note']);

		if($params['tab'] == 'controllers' ) {

			$entry['class'] = $params['class'];
			$entry['section_id'] = $params['section_id'];
		}

		$entry['active'] = $this->getVar($params['active'],0);

		$returnData  = array();

		try {
			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert')
								->table($params['tab'])
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id();

			}
			else {
			// Существующая запись
				$this->pixie->db->query('update')
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

		$returnData 			= array_values($entry);
		$returnData['DT_RowId']	= 'tab-'.$params['tab'].'-'.$params['id'];


		$this->response->body = json_encode($returnData);
	}

	public function action_delEntry() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		$this->pixie->db->query('delete')
						->table($params['tab'])
						->where('id',$params['id'])
						->execute();


		// Если есть связанные страницы - обнуляем связь (section_id)
		if( $params['tab'] == 'sections' ) {

			$entries = $this->pixie->db->query('select')
										->table('controllers')
										->where('section_id',$params['id'])
										->execute()
										->as_array();

			$this->pixie->db->query('update')
							->table('controllers')
							->data(array('section_id' => 0))
							->where('section_id',$params['id'])
							->execute();


			// для изменения в общей таблице
			if( $entries ) {
				$returnData = array();
				foreach($entries as $entry) {
					$returnData[] = array('id' => 's'.$params['id'].'-c'.$entry->id);
				}

				$this->response->body = json_encode($returnData);
			}
		}
    }

}
?>
