<?php
namespace App;

class Page extends \PHPixie\Controller {

	protected $view;
	protected $auth;
	protected $user_role;
	protected $logmsg;

	public function before() {

		 $this->view = $this->pixie->view('main');
	}


	protected function is_logged(){

        if( $this->pixie->auth->user() == null )
            return false;

		$name = $this->pixie->auth->user()->login;
		$ctrl = $this->request->param('controller');

		$result = $this->pixie->db->query('select')->fields('X.role_name')
												->table('roles','X')
												->join(array('auth','Y'),array('Y.id','X.auth_id'),'LEFT')
												->where('Y.login',$name)
												->where('X.ctrl_name',strtolower($ctrl))
												->execute()
												->current();

		$this->user_role = strtolower($result->role_name);

		if( ! $this->user_role ) {
            $this->response->body = "You don't have the permissions to access this page";
            $this->execute=false;
            return false;
		}

        return true;
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
