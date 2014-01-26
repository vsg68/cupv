<?php
namespace App;

class Page extends \PHPixie\Controller {

	protected $_id;
	protected $view;
	protected $auth;
	protected $ctrl;
	protected $permissiion;
	const RIGHTS_ERROR = 'Не хватает прав для данной операции.';
	const NONE_LEVEL = 0;
	const READ_LEVEL = 1;
	const WRITE_LEVEL = 2;

	//protected $menuitems;


	public function before() {

		 $this->auth = $this->pixie->auth;
		 $this->ctrl = $this->request->param('controller');

		if( $this->ctrl == 'login' )
			return false;

		$this->view = $this->pixie->view('main');
	    $this->view->script_file = '';
		$this->view->css_file 	 = '';

		//~ if( $this->auth->user() ) {  // если пусто - юзер не зарегистрировался

			$name = $this->auth->user()->login ;

			$this->view->pages = $this->pixie->db->query('select','admin')
											->fields($this->pixie->db->expr('S.name, S.note, COALESCE(C.class,"#") AS link'))
											->table('sections','S')
											->join(array('controllers','C'),array('S.id','C.section_id'),'LEFT')
											->join(array('rights','P'),array('C.id','P.control_id'),'LEFT')
											->join(array('roles','R'),array('R.id','P.role_id'),'LEFT')
											->join(array('slevels','SL'),array('SL.id','P.slevel_id'),'LEFT')
											->join(array('auth','A'),array('A.role_id','P.role_id'),'LEFT')
											->where('A.login',$name)
											->where('S.active',1)
											->where('R.active',1)
											->where('C.active',1)
											->where('A.active',1)
											->where('C.order',0)
											->where('SL.slevel','>',0)
											->group_by('S.name')
											->execute();

		//~ }

		/* Определяем все контроллеры с одинаковыми ID */
		$this->view->menuitems = $this->pixie->db
										->query('select','admin')
										->fields('Y.*')
										->table('controllers','X')
										->join(array('controllers','Y'),array('Y.section_id','X.section_id'),'LEFT')
										->where('X.class',strtolower($this->request->param('controller')))
										->where('Y.active',1)
										->order_by('Y.order')
										->execute();

		// Проверка легитимности пользователя и его прав
        if( $this->ctrl != 'login' )
			$this->permissions = $this->is_approve();

	}

	/* Проверка на предоставление доступа к разделу */
	protected function is_approve(){

		if( $this->auth->user() == null )
			return 0;

		$name = $this->auth->user()->login;
		$ctrl = $this->request->param('controller');

		// вынимаем уровень доступа для страницы для пользователя
		$result = $this->pixie->db->query('select','admin')
									->fields('S.slevel')
									->table('controllers','C')
									->join(array('rights','P'),array('C.id','P.control_id'),'LEFT')
									->join(array('roles','R'),array('R.id','P.role_id'),'LEFT')
									->join(array('slevels','S'),array('S.id','P.slevel_id'),'LEFT')
									->join(array('auth','A'),array('A.role_id','P.role_id'),'LEFT')
									->where('A.login',$name)
									->where('C.class',$ctrl)
									->where('R.active',1)
									->where('C.active',1)
									->where('A.active',1)
									->execute()
									->current();

		return $this->getVar($result->slevel,0);
    }

	protected function noperm() {

		$view = $this->pixie->view('form_alert');
		$view->errorMsg = "У вас нет прав для выполнения  данной операции";
		$this->response->body = $view->render();
	}

	/* Берем или значение или значение по умолчанию */
	protected function getVar(& $var, $val=null) {

		return isset($var) ? $var : $val ;
	}

	protected function action_searchdomain() {

		$test = $this->request->post('query');

		// Готовлю ответ в нужном формате
		$arr['suggestions'] = array();


		if(  preg_match('/^[^@]+@/', $test, $match_arr)) {

			$test = preg_replace('/^[^@]+@/','',$test);

			$domains = $this->pixie->db
								->query('select')
								->fields('domain_name')
								->table('domains')
								->where('domain_name', 'like', $test.'%')
								->where('and', array('delivery_to','virtual'))
								->execute();

			foreach($domains as $domain) {
			// заполняю массив данных доменами
				array_push( $arr['suggestions'], $match_arr[0].$domain->domain_name );
			}
		}

        $this->response->body = json_encode($arr);
    }

    protected function action_searchMailbox() {

		if(! $test = $this->request->get('term') )
			return false;

		// Готовлю ответ в нужном формате
		$arr = array();

		$entries = $this->pixie->db->query('select')
									->fields('mailbox')
									->table('users')
									->where('mailbox', 'like', $test.'%')
									->execute();

		foreach($entries as $entry) {
		// заполняю массив данных доменами
			array_push( $arr, $entry->mailbox );
		}

        $this->response->body = json_encode($arr);
    }

	public function action_view() {

		// Проверка легитимности пользователя и его прав
        if( $this->permissions == $this::NONE_LEVEL )
			return  $this->noperm();

		if( file_exists($_SERVER['DOCUMENT_ROOT'].'/js/'.$this->ctrl.'.js') ) {
			$this->view->script_file = '<script type="text/javascript" src="../js/'.$this->ctrl.'.js"></script>';
		}

		if( file_exists($_SERVER['DOCUMENT_ROOT'].'/css/'.$this->ctrl.'.css') ) {
			$this->view->css_file = '<link rel="stylesheet" type="text/css" href="/css/'.$this->ctrl.'.css" />';
		}

		// Подключаем файл, с названием равным контроллеру
		$this->view->subview = $this->ctrl;

		$this->response->body = $this->view->render();
    }

	 /* получаем название имеющихся контроллеров */
	protected function get_ctrl() {

		$file_arr = Array();
		foreach( glob(dirname(__FILE__).'/Controller/*.php') as $name ) {

			preg_match('/([^\/]+)\.php$/',$name, $matches);
			if( isset($matches[1]) )
				$file_arr[strtolower($matches[1])] = '';
		}

		return $file_arr;
	}

	/*
	 * Блокировка специальных символов при вводе
	 */
	public function blockspechars(&$row) {
			$row = htmlentities( $row, ENT_QUOTES);
	}

	/*
	 * Функция добавляет элементы массива, для правильного отображения в таблице передачи
	 */
	protected function DTPropAddToObject($rows,$tab,$class) {

		$data = array();

		if( count($rows) ) {

			foreach($rows as $row) {

				if( $tab ) {
					 $row->DT_RowId =  'tab-'.$tab.'-'.$row->id;
				}
				if( $class ) {
					$row->DT_RowClass = $class;
				}
				unset($row->id);
				$data[] = $row;
	//print_r($data);exit;
			}
		}
		return $data;
	}

}
