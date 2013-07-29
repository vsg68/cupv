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

	public function before_1() {

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
	}

	/* Проверка на предоставление доступа к разделу */
	protected function is_approve($security_level = 0){

		$name = $this->pixie->auth->user()->login;
		$ctrl = $this->request->param('controller');
		$act  = $this->request->param('action');

		$result = $this->pixie->db->query('select')
									->fields($this->pixie->db->expr('count(*) as cnt'))
									->table('roles','X')
									->join(array('auth','Y'),array('Y.id','X.auth_id'),'LEFT')
									->where('Y.login',$name)
									->where('X.control_name',strtolower($ctrl))
									//->where('X.action_name',strtolower($act))  //задел на будущее
									->where('X.slevel','>=',$security_level)
									->execute()
									->current();
		if(	$result->cnt )
			return true;
		else
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
