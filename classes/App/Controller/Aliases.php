<?php
/*

 */
namespace App\Controller;

class Aliases extends \PHPixie\Controller {

	private $logmsg;
	private $alias_name;

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

    public function action_index() {

        $view = $this->pixie->view('main');

		$view->subview 		= 'aliases_main';
		$view->script_file	= '<script type="text/javascript" src="/aliases.js"></script>';
		$view->css_file 	= '<link rel="stylesheet" href="/aliases.css" type="text/css" />';

		$aliases_arr = array();

        $aliases = $this->pixie->db
								->query('select')
								->fields('alias_name','delivery_to')
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

			array_push( $aliases_arr[$alias->alias_name], $alias->delivery_to);
		}


		$view->aliases_arr = $aliases_arr;

        $this->response->body = $view->render();
    }

    public function action_view() {

		$view = $this->pixie->view('aliases_view');
		// вывод лога
		$view->log = isset($this->logmsg) ?  $this->logmsg : '';

 		$view->alias_name = isset( $this->alias_name ) ? $this->alias_name : $this->request->post('id');

		$view->aliases = $this->pixie->db
								->query('select')->table('aliases')
								->where('alias_name',$view->alias_name)
								->execute();


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

			// Ошибки имели место
			if( isset( $this->logmsg ) ) {

				if ( $params['alias'] ) {

					$this->alias_name = $params['alias'];
					$this->action_view();
				}
				else
					$this->action_new();
			}
			else {
				// Возвращаемся обратно в форму редактирования
				$this->alias_name = $params['alias'];

				$this->logmsg = "<span class='success'>Изменено</span>";
				$this->action_view();
			}

		}

	}
}
?>
