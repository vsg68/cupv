<?php

namespace App\Controller;

class SquidACL extends \App\Page {
	
	protected $squidacl_fname;
	
	public function before() {
		$this->squidacl_fname = '/home/vsg/squid.acl.tmp';
		\App\Page::before();
	}
	
	public function action_showTable() {

		$acls['aaData'] = $this->getString(0, $this->squidacl_fname);
		$this->response->body = json_encode($acls);
		
	}
	
	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $tab = $this->request->post('t') )
			return;

		$this->_id 	= $this->request->param('id');
		$init	 	= $this->request->post('init');
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;
		$data 		= array();

		if( $this->_id ) {
			$view->data = $this->getString($this->_id, $this->squidacl_fname);
		}
		// вынимаем данные из массива
		if( $tab == 'tab_squidacl_data' && $data ) {
			$view->entry = explode(' '.$data[3])[$init];
		}
		
       $this->response->body = $view->render();
    }

	protected function getString($num, $fname) {
		/* 1. проверка на существование
		 * 2. открытие, чтение
		 * 3. закрытие
		 */
		try{ 
			$i = 1;
			$handle = fopen($fname, "r");

			while (($line = fgets($handle)) !== false) {
				// берем построчно файл и закидываем в массив.
				if( preg_match('/^#?acl\s+/', $line) ) {
											
					$matches = preg_split('/\\t/', trim($line));

					$data[] = array('acl'		=> ltrim($matches[0],'#'),
									'name'		=> $matches[1],
									'type'		=> $matches[2],
									'data'		=> $matches[3],
									'comment'	=> $this->getVar($matches[4]),
									'active'	=> ( preg_match('/^#/', $matches[0]) ? 0 : 1 ),
									'DT_RowId'	=> 'tab-'.$this->ctrl.'-'.$i
									);
					
					// получаем ее и выходим
					if( $num == $i ) {
						$data = $data[$i-1];
						break;
					}

					$i++;							
				}			
			}
			fclose($handle);
			return  $data;
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
