<?php

namespace App\Controller;

class SquidACL extends \App\Page {
	
	protected $squidacl_fname;
	
	public function before() {
		$this->squidacl_fname = '/home/vsg/squid.acl.tmp';
		\App\Page::before();
	}
	
	public function action_showTable() {
		$acls['aaData'] = $this->getACL($this->squidacl_fname);
		$this->response->body = json_encode($acls);
	}
	
	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$pid		= $this->request->post('init');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;
		$view->id 	= $this->_id;

		if ($tab != 'squidacl') {
			 $view->pid = $pid;
		}	 
	
		$i	  = ($tab == 'squidacl') ? $this->_id : $pid;
		$line = $this->getACL($this->squidacl_fname)[$i];
		
		$line2array = explode(' ', $line['data']);

		$view->data = ($tab == 'squidacl') ? $line : $line2array[$this->_id];
					
		$this->response->body = $view->render();
    }
	
	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();
		
		$this->_id = $this->request->param('id');
		
		if( ! isset($this->_id) ) return;

		$line 	 = $this->getACL($this->squidacl_fname)[$this->_id];
		$entries = explode(' ', $line['data']);

		$i = 0;
		foreach($entries as $entry) {
			$data[$i] = array($entry,
							 'DT_RowId' => 'tab-squidacl_data-'.$i,
							 'DT_RowClass' => 'gradeA'
							);
			$i++;				
		}

		$this->response->body = json_encode($data);

    }
    
    //~ protected function acl_str($var) {
		//~ 
	//~ }
	
	// Функция фильтрации файла ACL
	protected function acl_str($var) {
		return preg_match('/^#?acl\s+/', $var);
	}

	// Возвращаем или все строки или определенную из файла ACL
	protected function getACL($fname) {

		try{ 
			$lines = file($fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

			if( ! is_array($lines) ) {
				throw new \Exception("Файл {$fname} не парсится!");
			}

			$i = 0; // 0 - у нас новая запись
			foreach( array_filter( $lines, array($this,'acl_str')) as $line ) {
				$matches = preg_split('/\\t/', $line);
				$data[$i] = array('acl'		=> ltrim($matches[0],'#'),
								'name'		=> $matches[1],
								'type'		=> $matches[2],
								'data'		=> $matches[3],
								'comment'	=> $this->getVar($matches[4]),
								'active'	=> ( preg_match('/^#/', $matches[0]) ? 0 : 1 ),
								'DT_RowId'	=> 'tab-'.$this->ctrl.'-'.$i
								);
				$i++;
			}				
			
			return  $data;
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
			return;
		}
		
	
	}
	
	// Сохранение массива в файл
	protected function setACL($acls, $fname) {
		try {
		
		}	
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
			return;
		}
	}
 }

?>
