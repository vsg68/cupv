<?php
namespace App;

class Page extends \PHPixie\Controller {

	protected $_id;
	protected $view;
	protected $auth;
	protected $logmsg;
	protected $permissiion;
	const RIGHTS_ERROR = 'Не хватает прав для данной операции.';
	const NONE_LEVEL = 0;
	const READ_LEVEL = 1;
	const WRITE_LEVEL = 2;

	//protected $menuitems;


	public function before() {

		 $this->auth = $this->pixie->auth;

		 if( $this->request->param('controller') == 'login' )
			return false;

		 $this->view = $this->pixie->view('main');

		 /* Определяем все контроллеры с одинаковыми ID */
		 $this->view->menuitems = $this->pixie->db
										->query('select')
										->fields('Y.*')
										->table('controllers','X')
										->join(array('controllers','Y'),array('Y.section_id','X.section_id'),'LEFT')
										->where('X.class',strtolower($this->request->param('controller')))
										->where('Y.active',1)
										->order_by('Y.arrange')
										->execute();

		// Проверка легитимности пользователя и его прав
       // if( $this->request->param('controller') != 'login' )
		//	$this->permissions = $this->is_approve();

	}

	/* Проверка на предоставление доступа к разделу */
	protected function is_approve(){

		if( $this->auth->user() == null )
			return 0;

		$name = $this->auth->user()->login;
		$ctrl = $this->request->param('controller');

		// вынимаем уровень доступа для страницы для пользователя
		$result = $this->pixie->db->query('select')
									->fields('S.slevel')
									->table('controllers','C')
									->join(array('page_roles','P'),array('C.id','P.control_id'),'LEFT')
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

		$this->view->subview = '';
		$this->view->script_file = '';
		$this->view->css_file = '';
		$this->response->body = "У вас нет прав для выполнения  данной операции";
		$this->execute=false;
		return false;
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
}
