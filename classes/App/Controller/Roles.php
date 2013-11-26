<?php

namespace App\Controller;

class Roles extends \App\Page {

    public function action_view() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$this->view->subview 		= 'roles';

		$this->view->script_file	= '<script type="text/javascript" src="/js/roles.js"></script>';
		$this->view->css_file 		= '';//<link rel="stylesheet" href="/roles.css" type="text/css" />';

		$roles = array();

		$this->view->entries = $this->pixie->db->query('select')
											->table('roles')
											->execute();

        $this->response->body = $this->view->render();
    }

	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();


		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$view 		= $this->pixie->view('form_roles');
		$view->pid	= $this->request->post('init');
		$view->tab  = $tab;


		$data = $this->pixie->db->query('select')
										->table($tab)
										->where( ( $tab == 'rights' ? 'role_' : '').'id',$this->_id )
										->execute()
										->current();

		if( $tab == 'rights' ) {

			$view->slevels = $this->pixie->db->query('select')
											->table('slevels')
											->execute()
											->as_array();

			// передаем ссылку на контроллер для дальнейшего отслеживания
			$view->ctrl = isset($data->id) ? '' : $this->_id;
		}

	   $view->data = $data;
       $this->response->body = $view->render();
    }

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $this->_id = $this->request->param('id'))
			return;

		try {
			$entries = $this->pixie->db->query('select')
									->fields(array('S.name','sect_name'),
											 array('C.name','ctrl_name'),
											 array('C.class','ctrl_class'),
											 array($this->pixie->db->expr('COALESCE(L.name,"NONE")'),'slevel'),
											 array('C.active','c_active'),
											 array('P.role_id','role_id'),
											 array('C.id','ctrl_id')
											 )
									->table('controllers','C')
									->join(array('sections','S'),array('S.id','C.section_id'),'LEFT')
									->join( array('rights','P'),
												array(
														array('P.role_id',$this->pixie->db->expr($this->_id)),
														array('P.control_id','C.id')
													))
									->join(array('slevels','L'),array('L.id','P.slevel_id'),'LEFT')
									->order_by('S.name')
									->order_by('C.name')
									->execute()
									->as_array();

			$data = array();

			foreach($entries as $entry) {

				$data[] = array( $entry->sect_name,
								 $entry->ctrl_name,
								 $entry->slevel,
								 $entry->c_active,
								 'DT_RowId'    => 'tab-rights-'.( $entry->role_id ? $entry->ctrl_id : '0_'.$entry->ctrl_class),
								 'DT_RowClass' => ($entry->slevel == 'WRITE') ? 'gradeA' : ( ($entry->slevel == 'NONE') ? 'gradeX' : '')
								);
			}

			$this->response->body = json_encode($data);
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}

    }



}
?>
