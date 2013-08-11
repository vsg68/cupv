<?php
/*

 */
namespace App\Controller;

class Admin extends \App\Page {

	private $section_id;
	private $pages;

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

 		$this->view->script_file	= '<script type="text/javascript" src="/admin.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/admin.css" type="text/css" />';

		$this->view->subview = 'admin_main';

		$this->view->sections = $this->pixie->db->query('select')
												->table('sections')
												->execute();

		$this->view->controllers = $this->pixie->db->query('select')
													->fields($this->pixie->db->expr('S.id AS s_id, S.name AS s_name, C.class AS c_class'))
													->table('controllers','C')
													->join(array('sections','S'),array('C.section_id','S.id'))
													->execute();

		// Выбираем контроллеры(класс) массив
		$this->view->options = $this->get_ctrl();

		$this->view->sections_block = $this->action_single();

        $this->response->body	= $this->view->render();
    }

	public function action_single() {

		$view 		= $this->pixie->view('admin_view');
		$view->log 	= $this->getVar($this->logmsg,'');

		if( ! $this->request->param('id'))
			//return "<img class='lb' src=/domains.png />";
			return;

		$this->_id = $this->getVar($this->_id, $this->request->param('id'));

		$section = $this->pixie->db
								->query('select')->table('sections')
								->where('id', $this->_id)
								->execute()
								->current();
		//Если ответ пустой
		if( ! count($section) )
			return "<strong>Раздела с ID ".$this->_id." не существует.</strong>";

		$view->section = $section;
		$view->options = $this->get_ctrl();

		// Собираем все контроллеры(страницы)
		$view->controllers = $this->pixie->db->query('select')->table('controllers')
											->where('section_id', $this->_id)
											->order_by('arrange')
											->execute();

		// Если массив пуст - инициируем пустым массивом
	//	$view->controllers = $this->getVar($view->controllers, array());

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
    }

	public function action_new() {

		$view 	   = $this->pixie->view('admin_new');
		$view->log = $this->getVar($this->logmsg,'<strong>Создание нового раздела.</strong>');

		// Рисуем логотип, если такой файл есть.
		$path_arr   = explode('\\',get_class($this));
		$logo_image = dirname( $_SERVER["SCRIPT_FILENAME"] ).'/'. strtolower($path_arr[2]).'.png';
		$view->logo	= file_exists($logo_image) ? "<img src ='/".basename($logo_image)."' />" : '';

		$this->response->body = $view->render();
	}

    public function action_add() {

       if ($this->request->method == 'POST') {

			$params = $this->request->post();
			unset($params['chk']);

			// обработка строк
//			array_walk($params,array($this,'sanitize'),'notempty');

			// Инициируем, чтоб не было ошибки при обработке несуществующего массива
			$params['ctrl_name'] = $this->getVar($params['ctrl_name'],array());

			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {
				//
				// Приходит обработка либо новой записи, либо изменение существующего
				//
				$entry = array( 'name' 		=> $params['section_name'],
								'note'		=> $this->getVar( $params['section_note'] ),
								'active'	=> $this->getVar( $params['active'],0 )
								);

				if ( ! isset($params['section_id']) ) {
				// новая запись
					$this->pixie->db->query('insert')->table('sections')
									->data($entry)
									->execute();

					$params['section_id'] = $this->pixie->db->insert_id();
				}
				else {
				// Запись существует
					$this->pixie->db->query('update')->table('sections')
									->data($entry)
									->where('id',$params['section_id'])
									->execute();
				}

			//
			// Обработка контроллеров
			//
				foreach ($params['fname'] as $key=>$fname ) {
					// Готовим массив данных
					$entry = array(	'name'  	 => $fname,
									'class' 	 => $params['ctrl_class'][$key],
									'section_id' => $params['section_id'],
									'arrange' 	 => $params['num'][$key],
									'active'	 => $params['stat'][$key]
									);

					if( $params['stat'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')->table('controllers')
										->where('id',$params['fid'][$key])
										->execute();
					}
					elseif( $params['fid'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')->table('controllers')
										->data($entry)
										->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('controllers')
										->data($entry)
										->where('id', $params['fid'][$key])
										->execute();
					}
				}

			}

			// Ошибки имели место
			if( isset( $this->logmsg ) ) {

				if ( $params['section_id'] ) {
					// Ошибка во время редактирования
					$this->_id = $params['section_id'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['section_id'];
		}

	}

}
?>
