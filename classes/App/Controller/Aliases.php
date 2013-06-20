<?php
/*

 */
namespace App\Controller;

class Aliases extends \PHPixie\Controller {

	private $logmsg;
	private $alias_name;
	private $alias_block;

    private function sanitize($value,$key,$method) {

		if( is_string($value) ) $value =  trim($value) ;

		switch ( $method ) {
			case 'empty':
				$value = isset($value) ? $value : '0';
				break;
			case 'notempty':
				if( $value == '' ) {
					 $this->logmsg .= "<span class='error'>Field $key can not be empty</span>";
				 }
				break;
			case 'net':
				if( !preg_match ('!((\d+\.)+\d+(/\d+)?,?\s*)+!', $value) ) {
					$this->logmsg .= "<span class='error'>Wrong entry for net in field $key</span>";
				}
				break;
			case 'is_number':
				if( is_number($value) ) {
					$this->logmsg .= "<span class='error'>Wrong entry for field $key</span>";
				}
				break;
			case 'is_mail':
				if ( ! preg_match('/(\w+)@(\w+\.)+(\w+)/',$value) ) {
					$this->logmsg .= "<span class='error'>Wrong entry for mail in field $key</span>";
				}
				break;
			default:

		}
	}

    public function action_view() {

        $view = $this->pixie->view('main');

		$view->subview 		= 'aliases_main';
		$view->script_file	= '<script type="text/javascript" src="/aliases.js"></script>';
		$view->css_file 	= '<link rel="stylesheet" href="/aliases.css" type="text/css" />';

		$aliases_arr = array();

        $aliases = $this->pixie->db
								->query('select')
								->fields('alias_name','delivery_to','active')
								->table('aliases')
								->order_by('alias_name')
								->execute();

		$view->domains = $this->pixie->db
								->query('select')
								->fields('domain_name')
								->table('domains')
								->where('delivery_to','virtual')
								->execute();

		foreach ($aliases as $alias) {

			if( ! isset($aliases_arr[$alias->alias_name]) )
				$aliases_arr[$alias->alias_name] = array();
			// Если алиас неактивный - форматируем
			$alias->delivery_to = ( $alias->active == 1 ) ? $alias->delivery_to : "<strike>".$alias->delivery_to."</strike>";

			array_push( $aliases_arr[$alias->alias_name], $alias->delivery_to);
		}

		$view->aliases_arr 		= $aliases_arr;
		$view->aliases_block 	= $this->action_single();

        $this->response->body	= $view->render();
    }


	public function action_single() {

		$view 				= $this->pixie->view('aliases_view');
		$view->log 			= isset($this->logmsg) ?  $this->logmsg : '';

		$view->alias_name 	= $this->alias_name; 		// Вдруг была ошибка?

		if( ! $this->request->get('name') )
			return "<img class='lb' src=/mail.png />";

		$view->alias_name 	= $this->request->get('name');
		$view->aliases = $this->pixie->db
								->query('select')->table('aliases')
								->where('alias_name',$view->alias_name)
								->execute()
								->as_array();

		// Если адрес полностью удалили
		if( count($view->aliases) == 0 )
			return "<strong>".$view->alias_name.":</strong> Такого адреса нет.";

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
	}

	public function action_new() {

		$view 		= $this->pixie->view('aliases_new');
		$view->log 	= isset($this->logmsg) ?  $this->logmsg : '';

		$this->response->body = $view->render();
	}

	public function action_add() {

        if ($this->request->method == 'POST') {

			$params = $this->request->post();

			unset($params['chk']);

			// Инициируем, чтоб не было ошибки при обработке несуществующего массива
			if( ! isset($params['fwd']) ) $params['fwd'] = array();

			// Проверка на почтовый адрес
			array_walk( $params['fwd'] ,array($this,'sanitize'),'is_mail' );


			// Если нет ошибок заполнения - проходим в обработку
			if( ! isset($this->logmsg) ) {

				$is_exist = array();

				// если создали новое
				if( isset($params['newalias']) ) {

					// Проверка на почтовый адрес
					if ( ! preg_match('/(\w+)@(\w+\.)+(\w+)/',$params['newalias']) )
						$this->logmsg .= "<span class='error'>Wrong entry for mail</span>";

					if( ! isset($this->logmsg) ) {
						$params['alias'] = $params['newalias'];
					}
				}

				// Если ошибок все еще нет
				if( ! isset( $this->logmsg ) ) {
					// Обработка алиасов
					foreach ($params['fwd'] as $key=>$alias ) {

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
												'alias_name' => $params['alias'],
												'delivery_to'=> $params['fwd'][$key],
												'active'	 => $params['fwd_st'][$key]
											))->execute();
						}
						else {
						// Изменение
							$this->pixie->db->query('update')->table('aliases')
											->data(array(
												'alias_name' => $params['alias'],
												'delivery_to'=> $params['fwd'][$key],
												'active'	 => $params['fwd_st'][$key]
											))
											->where('alias_id', $params['fwd_id'][$key])
											->execute();
						}
					}
				}
			}

			// Ошибки имели место - возвращаем форму
			if( isset( $this->logmsg ) ) {

				if ( isset($params['alias']) ) {

					$this->alias_name = $params['alias'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['alias'];

		}

	}


}
?>
