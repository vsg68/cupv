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
       // if( ! $this->check_permissions() )
		//	return false;
	}

	/* Проверка на предоставление доступа к разделу */
	protected function is_approve(){

		//~ if(  $this->auth->user() )
			//~ return false;

		$name = $this->pixie->auth->user()->login;
		$ctrl = $this->request->param('controller');
//		$act  = $this->request->param('action');

		$result = $this->pixie->db->query('select')
									->fields($this->pixie->db->expr('if(strcmp("'.$ctrl.'","login")=0, 100, count(*)) as cnt'))
									->table('auth','A')
									->join(array('roles','R'),array('A.id','R.auth_id'),'LEFT')
									->join(array('controllers','C'),array('C.id','R.control_id'),'LEFT') // связь юзера и страницы
									->join(array('slevels','AL'),array('AL.id','R.slevel_id'),'LEFT') // какой уроветь доступа пользователя к странице
									->join(array('sections','S'),array('S.id','C.section_id'),'LEFT')  // что за раздел, к которому принадлежит страница
									->join(array('slevels','SL'),array('SL.id','S.slevel_id'),'LEFT')  // ее уровень доступа
									->where('A.login',$name)
									->where('S.active',1)
									->where('C.class',$ctrl)
									->where($this->pixie->db->expr('IFNULL(AL.slevel,0) >= SL.slevel'),1)
									->execute()
									->current();
//echo $name.",".$ctrl.",".$result->cnt."__"; exit;
		if(	$result->cnt )
			return true;

		return false;

    }

	protected function check_permissions(){

	//	if( ! $this->is_approve() )
			return true;

		$this->view->subview = '403';
		$this->response->body = $this->view->render();
		//$this->execute=false;
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
