<?php
/*

 */
namespace App\Controller;

class Admin extends \App\Page {

	private $section_id;

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

		$this->view->sections = $this->pixie->db
											->query('select')
											->table('sections')
											->execute();

		$this->view->sections_block = $this->action_single();

        $this->response->body	= $this->view->render();
    }

	public function action_single() {

		$view 		= $this->pixie->view('admin_view');
		$view->log 	= isset($this->logmsg) ?  $this->logmsg : '';

		if( ! $this->request->get('name') )
			//return "<img class='lb' src=/domains.png />";
			return;

		if( ! isset($this->section_id) )
			$this->section_id = $this->request->get('name');

		$section = $this->pixie->db
								->query('select')->table('sections')
								->where('id', $this->section_id)
								->execute()
								->current();


		$view->options = $this->get_ctrl();

		$view->slevels = $this->pixie->db
										->query('select')
										->table('slevels')
										->execute();

		//Если ответ пустой
		if( ! count($section) )
			return "<strong>Раздела с ID ".$this->section_id." не существует.</strong>";

		$view->section = $section;


		// Собираем все контроллеры(страницы)
		$view->controllers = $this->pixie->db
											->query('select')->table('controls')
											->where('section_id', $this->section_id)
											->execute();

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
    }

	public function action_new() {

		$view 		= $this->pixie->view('admin_new');
		$view->log 	= $this->getVar($this->logmsg));

		if( ! isset($this->logmsg) )
			$view->log = '<strong>Создание нового раздела.</strong>';

		$view->slevels = $this->pixie->db->query('select')->table('slevels')->execute();
		$view->options = $this->get_ctrl();

		// Рисуем логотип, если такой файл есть.
		$logo_image = dirname($_SERVER["SCRIPT_FILENAME"]) .'/'. strtolower(explode('\\',get_class($this))[2]).'.png';
		$view->logo	= file_exists($logo_image) ? $logo_image : '';

		$this->response->body = $view->render();
	}

    public function action_add() {

       if ($this->request->method == 'POST') {

			$params = $this->request->post();
			unset($params['chk']);

			// обработка строк
//			array_walk($params,array($this,'sanitize'),'notempty');

			// Инициируем, чтоб не было ошибки при обработке несуществующего массива
			if( ! isset($params['ctrl_class']) ) $params['ctrl_class'] = array();
			if( ! isset($params['ctrl_name']) )   $params['ctrl_name'] = array();
// Как обрабатывается картинка ?

			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {
				//
				// Приходит обработка либо новой записи, либо изменение существующего
				//
				$entry = array( 'name' 		=> $params['section_name'],
								'note'		=> $params['section_note'],
								'slevel_id' => $params['slevel_id'],
								'active'	=> $this->getVar($params['active'],0)
								);

				if ( ! isset($params['section_id']) ) {
				// новая запись
					$this->pixie->db->query('insert')->table('paramss')
									->data($entry)
									->execute();

				}
				else {
				// Запись существует
					$this->pixie->db->query('update')->table('sections')
									->data($entry)
									->where('id',$params['section_id'])
									->execute();
				}

				// Обработка алиасов
				foreach ($params['alias'] as $key=>$alias ) {

					if( $params['alias_st'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')->table('aliases')
										->where('alias_id',$params['alias_id'][$key])
										->execute();
					}
					elseif( $params['alias_id'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')->table('aliases')
										->data(array(
											'alias_name' => $alias,
											'delivery_to'=> $params['mailbox'],
											'active'	 => $params['alias_st'][$key]
										))->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('aliases')
										->data(array(
											'alias_name' => $alias,
											'delivery_to'=> $params['mailbox'],
											'active'	 => $params['alias_st'][$key]
										))
										->where('alias_id', $params['alias_id'][$key])
										->execute();
					}
				}

				// Обработка форварда
				foreach ($params['fwd'] as $key=>$fwd ) {

					if( $params['fwd_st'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')->table('aliases')
										->where('alias_id',$params['fwd_id'][$key])
										->execute();
					}
					elseif( $params['fwd_id'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')->table('aliases')
										->data(array(
											'alias_name' => $params['mailbox'],
											'delivery_to'=> $fwd,
											'active'	 => $params['fwd_st'][$key]
										))->execute();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('aliases')
										->data(array(
											'alias_name' => $params['mailbox'],
											'delivery_to'=> $fwd,
											'active'	 => $params['fwd_st'][$key]
										))
										->where('alias_id', $params['fwd_id'][$key])
										->execute();
					}
				}

			}

			// Ошибки имели место
			if( isset( $this->logmsg ) ) {

				if ( $params['params_id'] ) {
					// Ошибка во время редактирования
					$this->mailbox = $params['mailbox'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['mailbox'];
		}

	}

}
?>
