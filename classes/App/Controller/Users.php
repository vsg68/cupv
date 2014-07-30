<?php

namespace App\Controller;

class Users extends \App\Page {

	public function action_save() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		if( ! $params = $this->request->post() )
			return false;

        $params['md5password'] = md5($params['password']);

        if( ! $params['path'] )
            $params['path'] = NULL;

		try {
			$is_update = isset($params['is_new']) ? false : true;
            unset( $params['is_new'] );

			// Если в запрос поместить true -  предполагается UPDATE
			$this->pixie->orm->get("users")->values($params, $is_update)->save();
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
	}

    // Функция проверки почтового адреса
    public function action_validateEmail(){

        $mbox = $this->request->get("mbox");
        $id = $this->request->get("id");

        // запрос
        $sql = $this->pixie->db->query('select')
                                ->fields("domain_name")
                                ->table('domains')
                                ->where(array('delivery_to','virtual'));

        $result = $this->pixie->db->query('select')
                                    ->table('users')
                                    ->where(array(
                                                array('id','!=', $id),
                                                array("mailbox", $mbox),
                                    ))
                                    ->where(array('or',array(
                                        array('id', $id),
                                        array("mailbox", '!=', $mbox),
                                    )))
                                    ->where(array('or',array( $this->pixie->db->expr("SUBSTRING_INDEX('".$mbox."', '@', -1)"), 'NOT IN', $sql)))
                                    ->execute()->as_array();

        $this->response->body = count($result) ? false : true;
    }

    public function action_showTable(){

        $result = $this->pixie->db->query('select')
                            ->fields($this->pixie->db->expr("*, DATE_FORMAT(`last_login`, '%d-%m-%Y') as last_login"))
                            ->table('users')
                            ->order_by('mailbox')
                            ->execute()->as_array();

        $this->response->body = json_encode($result);
    }
    public function action_getMailboxes(){

        $result = $this->pixie->db->query('select')
                            ->fields($this->pixie->db->expr("mailbox AS id, mailbox AS value, id AS user_id"))
                            ->table('users')
                            ->order_by('mailbox')
                            ->where("active",1)
                            ->execute()->as_array();

        $this->response->body = json_encode($result);
    }


}
?>