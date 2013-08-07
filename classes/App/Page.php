<?php
namespace App;

class Page extends \PHPixie\Controller {

	protected $view;
	protected $auth;
	protected $user_level;
	protected $logmsg;
	const RIGHTS_ERROR = 'Не хватает прав для данной операции.';
	//~ const READ_LEVEL = 0;
	//~ const WRITE_LEVEL = 1;
	//~ const ADMIN_LEVEL = 2;
	//protected $menuitems;

	public function before() {

		 $this->view = $this->pixie->view('main');

		 $this->auth = $this->pixie->auth;

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
        if( $this->request->param('controller') != 'login' && ! $this->check_permissions()  )
			return false;
	}

	/* Проверка на предоставление доступа к разделу */
	protected function is_approve(){

		$name = $this->pixie->auth->user()->login;
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

	protected function check_permissions(){

		if( ! $this->is_approve() )
			return true;

		$this->view->subview = '403';
		$this->response->body = $this->view->render();
		$this->execute=false;
		return false;
	}

    //~ protected function sanitize($value,$key,$method) {
//~
		//~ if( is_string($value) ) $value =  trim($value) ;
//~
		//~ switch ( $method ) {
			//~ case 'empty':
				//~ $value = isset($value) ? $value : '0';
				//~ break;
			//~ case 'notempty':
				//~ if( $value == '' ) {
					 //~ $this->logmsg .= "<span class='error'>Field $key can not be empty</span>";
				 //~ }
				//~ break;
			//~ case 'net':
				//~ if( !preg_match ('!((\d+\.)+\d+(/\d+)?,?\s*)+!', $value) ) {
					//~ $this->logmsg .= "<span class='error'>Wrong entry for net in field $key</span>";
				//~ }
				//~ break;
			//~ case 'is_number':
				//~ if( is_number($value) ) {
					//~ $this->logmsg .= "<span class='error'>Wrong entry for field $key</span>";
				//~ }
				//~ break;
			//~ case 'is_mail':
				//~ if ( ! preg_match('/(\w+)@(\w+\.)+(\w+)/',$value) ) {
					//~ $this->logmsg .= "<span class='error'>Wrong entry for mail in field $key</span>";
				//~ }
				//~ break;
			//~ default:
//~
		//~ }
	//~ }

	/* Берем или значение или значение по умолчанию */
	protected function getVar(& $var, $val=null) {

		return isset($var) ? $var : $val ;
	}
}
