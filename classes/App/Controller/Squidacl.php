<?php

namespace App\Controller;

class SquidACL extends \App\Page {

	protected $squidacl_fname;

	public function before() {
		$this->squidacl_fname = '/home/vsg/squid.acl.tmp';
		
		if(! file_exists($this->squidacl_fname) ) {
			throw new \Exception("Файл {$this->squidacl_fname} не найден");
		}
		$this->data_dir = dirname($this->squidacl_fname).'/acl';
		
		\App\Page::before();
	}

	public function action_showTable() {

		$lines = $this->file2Array();
		foreach( $lines  as $key => $line ) {
			$matches = $this->split_str($line);
			$acls['aaData'][$key] = array('name'	=> $matches[1],
										'type'		=> $matches[2],
										'comment'	=> ltrim($this->getVar($matches[4]), '#'),
										'active'	=> ( preg_match('/^#/', $matches[0]) ? 0 : 1 ),
										'DT_RowId'	=> 'tab-'.$this->ctrl.'-'.$key
										);
		}
		$this->response->body = json_encode($acls);
	}

	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $tab = $this->request->post('t') )
			return;
		
		$this->_id = $this->request->param('id');
		
		if( ! isset($this->_id) )  // если никаких значений
			return;
		
		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;
		$view->id 	= $this->_id;
		$pid 		= $this->request->post('init');
		
		if ($tab != 'squidacl') {
			$view->pid	= $pid;
		}

		$lines = $this->file2Array($pid);  // Массив из нужного файла
		
		$view->data = preg_match('/00$/', $this->_id) ?  array() : $this->split_str($lines[$this->_id]);
		$this->response->body = $view->render();
    }

	public function action_records() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		$lines = $this->file2Array( $this->request->param('id') ); // строка в файле
		$returnData = array();
		foreach( $lines  as $key => $line ) {
			$matches = $this->split_str($line);
			$returnData[$key] = array('name'	=> ltrim($matches[0],'#'),
									'comment'	=> ltrim($this->getVar($matches[1]), '#'),
									'active'	=> ( preg_match('/^#/', $matches[0]) ? 0 : 1 ),
									'DT_RowClass' => 'gradeA',
									'DT_RowId'	=> 'tab-squidacl_data-'.$key
									);
		}
		$this->response->body = json_encode($returnData);
    }

    public function action_edit() {

		if( ! $params = $this->request->post() )
			return;

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		try {
			$pid 	= $this->getVar($params['pid']);
			$id  	= $params['id'];
			$tab 	= $params['tab'];

			$params['active'] = $this->getVar($params['active'],0);
			
			unset($params['tab'],$params['id']);

			// Работаем с массивом
			$fileArr = $this->file2Array($pid);
			$id = preg_match('/00$/', $id) ? count($fileArr) : $id;
			
			if( isset($pid) ) { // работаем с данными строки
				$str 			= $this->file2Array()[$pid];
				$fname 			= $this->split_str($str)[3];
				$fileArr[$id] 	= $this->join_arr( array( 
														($params['active'] ? '' : '#').$params['name'],
														'#'.$params['comment'],
														));
			}
			else {
				$fname 			= $this->squidacl_fname;
				$fileArr[$id] 	= $this->join_arr( array($params['active'] ? 'acl' : '#acl',
														$params['name'],
														$params['type'],
														$this->data_dir.'/'.strtolower($params['name']).'.acl',
														'#'.$params['comment'],
														));
			}
			// Пишем в файл
			if( ! file_put_contents( $fname, implode("\n", $fileArr), LOCK_EX) ) {
				throw new \Exception("Ошибка при записи в файл {$fname}.");
			}	

			$returnData 				= $params;
			$returnData['DT_RowClass']	= isset($pid) ? 'gradeA' : '';
			$returnData['DT_RowId'] 	= 'tab-'.$tab.'-'.$id;

			$this->response->body = json_encode($returnData);
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
	}


    public function action_delEntry() {

		if( ! $params = $this->request->post() )
			return;

		if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

		try {
			$pid 	= $this->getVar($params['pid']);
			$id  	= $params['id'];
			$tab 	= $params['tab'];

			// Работаем с массивом
			$fileArr = $this->file2Array($pid);
			
			if( isset($pid) ) { // работаем с данными строки
				$str 			= $this->file2Array()[$pid];
				$fname 			= $this->split_str($str)[3];
			}
			else {
				$data_fname = $this->split_str($fileArr[$id])[3];
				$fname		= $this->squidacl_fname;
				if( ! unlink($data_fname) ) {
					throw new \Exception("Невозможно удалить файл {$data_fname}.");
				}
			}
			unset($fileArr[$id]);		
				
			// Пишем в файл
			if( ! file_put_contents( $fname, implode("\n", $fileArr), LOCK_EX) ) {
				throw new \Exception("Ошибка при записи в файл {$fname}.");
			}	

			return;
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
	}

	// Функция фильтрации файла ACL
	// реакция только на строки начинающиеся с #acl | acl
	protected function acl_str($var) {
		return preg_match('/^#?acl\s+/', $var);
	}

	protected function file2Array($pid='') {

		$lines 	 = file($this->squidacl_fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$fileArr = array_values(array_filter( $lines, array($this,'acl_str')));
		
		if( is_numeric($pid) ) {

			$this->data_fname = $this->split_str($fileArr[$pid])[3];
			$fileArr = file_exists( $this->data_fname ) ? file($this->data_fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : array();
		}

		return $fileArr;
	}

	// Делаем из строки массив
	public function split_str($str) {
		return preg_split('/\\t+/', $str); 
    }

	// Делаем из массива строку
	public function join_arr($arr) {
		return implode("\t", $arr);  
	}

 }

?>
