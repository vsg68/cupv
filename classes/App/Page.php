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
        $this->ctrl = strtolower($this->request->param('controller'));

		if( $this->ctrl == 'login' )
			return false;

		$this->view = $this->pixie->view('main');
	    $this->view->permission = '';
        $this->view->script_file = '';
		$this->view->css_file 	 = '';
        $this->view->ctrl 	 = $this->ctrl;
        $this->view->pages = '';

		 if( $this->auth->user() ) {  // если пусто - юзер не зарегистрировался

			$name = $this->auth->user()->login ;
        // $name = "vsg";

			$result = $this->pixie->db->query('select','admin')
											->fields($this->pixie->db->expr('S.*'))
											->table('sections','S')
											->join(array('rights','P'),array('S.id','P.section_id'),'LEFT')
											->join(array('roles','R'),array('R.id','P.role_id'),'LEFT')
											->join(array('slevels','SL'),array('SL.id','P.slevel_id'),'LEFT')
											->join(array('auth','A'),array('A.role_id','P.role_id'),'LEFT')
											->where('A.login',$name)
											->where('S.active',1)
											->where('R.active',1)
											->where('A.active',1)
											->where('SL.slevel','>',0)
											// ->group_by('S.name')
											->execute()->as_array();

            $this->view->pages = json_encode($result);
		 }


		// Проверка легитимности пользователя и его прав
        if( $this->ctrl != 'login' )
			 // $this->permissions = 2;
			$this->permissions = $this->is_approve();
 
	}

	/* Проверка на предоставление доступа к разделу */
	protected function is_approve(){

		if( $this->auth->user() == null ) {

            $this->redirect("/");
			return 0;
        }
		$name = $this->auth->user()->login;
		$ctrl = $this->request->param('controller');

		// вынимаем уровень доступа для страницы для пользователя
		$result = $this->pixie->db->query('select','admin')
									->fields('S.slevel')
									->table('sections','C')
									->join(array('rights','P'),array('C.id','P.section_id'),'LEFT')
									->join(array('roles','R'),array('R.id','P.role_id'),'LEFT')
									->join(array('slevels','S'),array('S.id','P.slevel_id'),'LEFT')
									->join(array('auth','A'),array('A.role_id','P.role_id'),'LEFT')
									->where('A.login',$name)
									->where('C.link',$ctrl)
									->where('R.active',1)
									->where('C.active',1)
									->where('A.active',1)
									->limit(1)
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

	public function action_view() {

        $this->view->permissions  = $this->permissions;
        $this->view->WRITE_LEVEL = $this::WRITE_LEVEL;
        $this->view->NONE_LEVEL  = $this::NONE_LEVEL;

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

    public function action_get_menulist() {

        $menuitems = array();
        $items = $this->pixie->db
                                ->query('select','admin')
                                ->fields($this->pixie->db->expr('CONCAT("/",Y.class) AS href, Y.name AS value'))
                                ->table('controllers','X')
                                ->join(array('controllers','Y'),array('Y.section_id','X.section_id'),'LEFT')
                                ->where('X.class',$this->ctrl)
                                ->where('Y.active',1)
                                ->where('Y.class','!=',$this->ctrl)
                                ->order_by('Y.order')
                                ->execute()->as_array();

//        $menuitems['name'] = array("href"=>"#","name"=>"Меню");
        $menuitems['name'] = "Меню";
        $menuitems['submenu'] = $items;

        $this->response->body = json_encode($menuitems);
    }

    public function action_select(){

        $mbox = $this->request->get("mbox");
        $user_id = $this->request->get("user_id");
        $q = $this->request->get("q");

        if( $q == "alias" ){
            $result = $this->pixie->db->query('select')->table('aliases')
                ->where(array("delivery_to",$mbox))
                ->where(array("alias_name","!=", $mbox))
                ->execute()->as_array();
        }
        elseif( $q == "fwd" ){
            $result = $this->pixie->db->query('select')->table('aliases')
                ->where(array("alias_name", $mbox))
                ->execute()->as_array();
        }
        elseif( $q == "group" ){
            $result = $this->pixie->db->query('select')
                    ->table('lists',"L")
                    ->fields($this->pixie->db->expr("L.id, G.id as group_id, G.name, G.active"))
                    ->join(array("groups","G"),array("G.id","L.group_id"),"LEFT")
                    ->where('L.user_id', $user_id)
                    ->execute()->as_array();
        }

        $this->response->body = json_encode($result);
    }

}
