<?php
namespace App\Controller;

class Aliases extends \App\Page {

	public function action_showTable() {

		$aliases = $this->pixie->db->query('select')
								->fields($this->pixie->db->expr('A.id,
																ifnull(U1.username,"") as from_username,
																ifnull(U.username,"") as to_username,
																A.alias_name,
																A.delivery_to,
																A.active'))
								->table('aliases','A')
								->join(array('users','U'),array('U.mailbox','A.delivery_to'))
								->join(array('users','U1'),array('U1.mailbox','A.alias_name'))
                                ->order_by('A.alias_name')
								->execute()
								->as_array();


        $this->response->body = json_encode($aliases);
	}

	public function action_delEntry() {

		if( ! $params = $this->request->post() )
			return;

		try {
			$this->pixie->orm->get("aliases")->where('id',$params['id'])->delete_all();
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
    }

    public function action_save() {

        if( ! $params = $this->request->post() )
            return false;

        try {
            $is_update = $params['is_new'] ? false : true;
            unset( $params['is_new'], $params['from_username'], $params['to_username']);

            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->orm->get("aliases")->values($params, $is_update)->save();
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

}
?>
