<?php
/*

 */
namespace App\Controller;

class Auth extends \App\Page {

    public function action_ShowTable() {

		$returnData = $data = array();

		$entries = $this->pixie->db
							->query('select')
							->fields('id', 'login', 'note', 'passwd', array('R.name', 'role'), 'active' )
							->table('auth')
							->join(array('roles','R'),array('role_id','R.id'))
							->execute();

		foreach( $entries as $entry ) {
			$data[] = array($entry->login,
							$entry->note,
							$entry->passwd,
							$entry->role,
							$entry->active,
							"DT_RowId" => $entry->id
							);
		}

		$returnData['aaData'] = $data;
        $this->response->body	= json_encode($returnData);
    }

}
?>
