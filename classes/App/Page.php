<?php
namespace App;

class Page extends \PHPixie\Controller {

	protected $view;
	protected $auth;
	protected $user_level;
	protected $logmsg;
	const RIGHTS_ERROR = 'Не хватает прав для данной операции.';
	const READ_LEVEL = 0;
	const WRITE_LEVEL = 1;
	const ADMIN_LEVEL = 2;


	public function before() {

		 $this->view = $this->pixie->view('main');
	}


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

    protected function sanitize($value,$key,$method) {

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
}
