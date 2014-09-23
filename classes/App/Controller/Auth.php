<?php
/*

 */
namespace App\Controller;

class Auth extends \App\Page {

    public function action_showTable() {

		$entries = $this->pixie->db->query('select', 'admin')
									->fields('A.id','A.login','A.note','A.active','A.role_id','R.name')
									->table('auth','A')
									->join(array('roles', 'R'), array('R.id','A.role_id'), 'LEFT')
									->execute()->as_array();

        $this->response->body = json_encode($entries);
    }

	public function action_save() {

        if( ! $params = $this->request->post() )
            return false;

        try {
            $is_update = $params['is_new'] ? false : true;
            
            if( $params['passwd'] ) 
            	$params['passwd'] = $this->auth->provider('password')->hash_password($params['passwd']);
            else
            	unset( $params['passwd']);

            unset( $params['is_new'], $params['name']);

            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->orm->get("auth")->values($params, $is_update)->save();

        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

	
	public function action_delEntry() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		try {
			$this->pixie->orm->get($params['tab'])->where('id',$params['id'])->delete_all();
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}
    }


}

?>
