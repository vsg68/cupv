<?php
/*

 */
namespace App\Controller;

class Aliases extends \App\Page {

	private $alias_id;
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

		$this->view->subview 		= 'aliases_main';
		$this->view->script_file	= '<script type="text/javascript" src="/aliases.js"></script>';
		$this->view->css_file 	= '<link rel="stylesheet" href="/aliases.css" type="text/css" />';

		// Проверка легитимности пользователя и его прав
        if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$aliases_arr = array();

        $this->view->aliases = $this->pixie->db->query('select')
									//->fields('alias_name','delivery_to','active')
									->table('aliases')
									->order_by('alias_name')
									->execute();

		$this->view->domains = $this->pixie->db->query('select')
												->fields('domain_name')
												->table('domains')
												->where('delivery_to','virtual')
												->execute();

		//~ foreach ($aliases as $alias) {
//~
			//~ if( ! isset($aliases_arr[$alias->alias_name]) )
				//~ $aliases_arr[$alias->alias_name] = array();
			//~ // Если алиас неактивный - форматируем
			//~ $alias->delivery_to = ( $alias->active == 1 ) ? $alias->delivery_to : "<strike>".$alias->delivery_to."</strike>";
//~
			//~ array_push( $aliases_arr[$alias->alias_name], $alias->delivery_to);
		//~ }

		//$this->view->aliases_arr 		= $aliases_arr;
		$this->view->aliases_block 	= $this->action_single();

        $this->response->body	= $this->view->render();
    }


	public function action_single() {

		$view 		= $this->pixie->view('aliases_view');
		$view->log 	= $this->getVar($this->logmsg,'');

		// Проверка на доступ
		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		if( ! $this->request->param('id'))
			return; // "<img class='lb' src=/mail.png />";

		$this->_id = $this->getVar($this->_id, $this->request->param('id'));

		$view->aliases = $this->pixie->db->query('select')
								->fields($this->pixie->db->expr('A.alias_id AS uid, B.*'))
								->table('aliases','A')
								->join(array('aliases','B'),array('A.alias_name','B.alias_name'),'LEFT')
								->where('alias_id',$this->_id)
								->execute()
								->as_array();

		// Если адрес полностью удалили
		if( count($view->aliases) == 0 )
			return "Такогй записи нет.";

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
	}

	public function action_new() {

		$view 		= $this->pixie->view('aliases_new');
		$view->log 	= $this->getVar($this->logmsg,'');

		// Проверка на доступ
		if( $this->permissions == $this::NONE_LEVEL ) {
			$this->noperm();
			return false;
		}

		$this->response->body = $view->render();
	}

	public function action_add() {

        if ($this->request->method == 'POST') {

			// Проверка на доступ
			if( $this->permissions != $this::WRITE_LEVEL ) {
				$this->noperm();
				return false;
			}

			$params = $this->request->post();

			unset($params['chk']);

			// Инициируем, чтоб не было ошибки при обработке несуществующего массива
			$params['fname'] = $this->getVar($params['fname'], array());

			// Проверка на почтовый адрес
			//array_walk( $params['fwd'] ,array($this,'sanitize'),'is_mail' );

			// Если ошибок все еще нет
			if( ! isset( $this->logmsg ) ) {
				// Обработка алиасов
				foreach ($params['fname'] as $key=>$alias ) {

					$entry = array(
									'alias_name' => $params['alias_name'],
									'delivery_to'=> $alias,
									'active'	 => $params['stat'][$key]
									);

					if( $params['stat'][$key] == 2 ) {
					// Удаление
						$this->pixie->db->query('delete')
										->table('aliases')
										->where('alias_id',$params['fid'][$key])
										->execute();
					}
					elseif( $params['fid'][$key] == 0 ) {
					// Новый
						$this->pixie->db->query('insert')
										->table('aliases')
										->data($entry)
										->execute();

						// нам не важно какой будет id, главное, что он ведет к нужному адресу
						$params['alias_uid'] = $this->pixie->db->insert_id();
					}
					else {
					// Изменение
						$this->pixie->db->query('update')->table('aliases')
										->data($entry)
										->where('alias_id', $params['fid'][$key])
										->execute();
					}
				}
			}

			// Ошибки имели место - возвращаем форму
			if( isset( $this->logmsg ) ) {

				if ( isset($params['alias_uid']) ) {

					$this->_id = $params['alias_uid'];
					$this->action_single();
				}
				else
					$this->action_new();
			}
			else
				$this->response->body = $params['alias_uid'];

		}

	}


}
?>
