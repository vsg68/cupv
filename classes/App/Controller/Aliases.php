<?php
namespace App\Controller;

class Aliases extends \App\Page {


    public function action_view() {

		// Проверка легитимности пользователя и его прав
		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview 		= 'aliases';
		$this->view->script_file	= '<script type="text/javascript" src="/js/aliases.js"></script>';
		$this->view->css_file 		= '';

        $this->response->body	= $this->view->render();
    }


	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;

		$view->is_alias_page = 1;

        $view->data = $this->pixie->db->query('select')
										->table($tab)
										->where('id',$this->_id)
										->execute()
										->current();

       $this->response->body = $view->render();
    }


	public function action_edit() {

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();


		if( ! $params = $this->request->post() )
			return;

		$returnData  = array();

		$entry = array('alias_name' => $params['alias_name'],
					   'delivery_to'=> $params['delivery_to'],
					   'alias_notes'=> $this->getVar($params['alias_notes']),
					   'active'		=> $this->getVar($params['active'],0)
					 );

		try {
			if ( $params['id'] == 0 ) {
				// новый пользователь
				$this->pixie->db->query('insert')
								->table( $params['tab'] )
								->data($entry)
								->execute();

				$params['id'] = $this->pixie->db->insert_id();

			}
			else {
			// Существующий пользователь
				$this->pixie->db->query('update')
								->table( $params['tab'] )
								->data($entry)
								->where('id',$params['id'])
								->execute();
			}
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
			return;
		}

		// смотрим есть ли у нас пользователи по этим адресам
		// Извращение нужно для правильного занесения в таблицу;
		$tmp = array();
		foreach( array($params['alias_name'], $params['delivery_to']) as $mbox ) {

			$data = $this->pixie->db->query('select')
									->table( 'users' )
									->where('mailbox', $mbox)
									->execute()
									->current();

			array_push($tmp, $this->getVar($data->username,'N/A'));

		}

		array_unshift($entry, $tmp[0], '->', $tmp[1]);

		// Массив, который будем возвращать
		$returnData 				= array_values($entry);
		$returnData['DT_RowId']		= 'tab-aliases-'.$params['id'];


		$this->response->body = json_encode($returnData);
	}

	public function action_showTable() {

		$returnData["aaData"] = array();
		$aliases = $this->pixie->db->query('select')
								->fields($this->pixie->db->expr('A.id,
																ifnull(U1.username,"N/A") as from_username,
																"->" as direction,
																ifnull(U.username,"N/A") as to_username,
																A.alias_name,
																A.delivery_to,
																A.alias_notes,
																A.active'))
								->table('aliases','A')
								->join(array('users','U'),array('U.mailbox','A.delivery_to'))
								->join(array('users','U1'),array('U1.mailbox','A.alias_name'))
								->order_by('A.delivery_to')
								->execute()
								->as_array();

		foreach($aliases as $alias) {
			$returnData["aaData"][] = array($alias->from_username,
											 $alias->direction,
											 $alias->to_username,
											 $alias->alias_name,
											 $alias->delivery_to,
											 $alias->alias_notes,
											 $alias->active,
											 'DT_RowId'=>'tab-aliases-'.$alias->id
											);
		}

        $this->response->body = json_encode($returnData);
	}

	public function action_delEntry() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

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

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

        if( ! $params = $this->request->post() )
            return false;

        try {
            $is_update = isset($params['is_new']) ? false : true;
            unset( $params['is_new'] );

            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->orm->get("aliases")->values($params, $is_update)->save();
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

}
?>
