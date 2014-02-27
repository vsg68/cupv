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

		$data  		= $this->getArrayOfItems($this->_id); // строка в файле
		$items		= explode(' ',$data[3]);
		$returnData = $this->DTPropAddToArray($items,'squidacl_data','gradeA');

		$this->response->body = json_encode($returnData);

    }
    //~ 
    //~ public function action_edit() {
//~ 
		//~ if( ! $params = $this->request->post() )
			//~ return;
//~ 
		//~ if( $this->permissions != $this::WRITE_LEVEL )
			//~ return $this->noperm();
//~ 
		//~ try {
			//~ $returnData  = array();
			//~ // формирование ID
			//~ $pid = $params['pid'];
			//~ $id  = $params['id'];
			//~ $tab = $params['tab'];
			//~ $i  = isset($pid) ? $pid : $id;
			//~ 
			//~ unset($params['tab'],$params['id'],$params['pid']);
			//~ 
			//~ // 1. Работаем с массивом
			//~ $line = $this->getACL($this->squidacl_fname)[$i];
			//~ 
			//~ // То, что будет возвращено в браузер
			//~ if( isset($pid) ) {
				//~ $data = explode(' ', $line['data']);
				//~ if( $id != '00') {
					//~ $data[$i] = $params['data'];
				//~ }
				//~ else {
					//~ array_push($data, $param['data']);
				//~ }
				//~ $line['data'] = implode(' ', $data);
			//~ }
			//~ else {
				//~ $line['name']	 = $params['name'];
				//~ $line['type']	 = $params['type'];
				//~ $line['comment'] = $params['comment'];
				//~ $line['active']	 = $this->getVar($params['active'],0);
				//~ 
				//~ if( $id == '00' ) {
					//~ $line['DT_RowId']	=> 'tab-'.$tab__;
					//~ array_push($lines, $line);
				//~ }
				//~ else {
					//~ $lines[$i] = $line;
				//~ }	
			//~ }
			//~ 
			//~ // То, что будет записано в файл
			//~ $params['name'] = $params['active'] ? $params['name'] : '#'.$params['name'];
			//~ 
			//~ // 2. Если изменения
			//~ if( $params['id'] != '00' ) {
				//~ 
			//~ }
			//~ // Если есть pid - значит мы работаем со строкой
			//~ $tab = $params['tab'];
			//~ unset($params['tab']);
//~ 
			//~ 
//~ 
			//~ $this->response->body = json_encode($returnData);
		//~ }
		//~ catch (\Exception $e) {
			//~ $this->response->body = $e->getMessage();
		//~ }
	//~ }
	//~ 
	// Функция фильтрации файла ACL
	protected function acl_str($var) {
		return preg_match('/^#?acl\s+/', $var);
	}

	// Возвращаем или все строки или определенную из файла ACL
	protected function getACL($fname) {

		try{ 
			$lines = $this->fileACL2Array( $fname );

			foreach( $lines  as $key => $line ) {
				$matches = preg_split('/\\t/', $line);
				$data[$key] = array('name'		=> $matches[1],
									'type'		=> $matches[2],
									'comment'	=> $this->getVar($matches[4]),
									'active'	=> ( preg_match('/^#/', $matches[0]) ? 0 : 1 ),
									'DT_RowId'	=> 'tab-'.$this->ctrl.'-'.$key
									);
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
	
	protected function fileACL2Array($fname) {

		try{ 
			$lines = file($fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

			if( ! is_array($lines) ) {
				throw new \Exception("Файл {$fname} не парсится!");
			}
			
			return array_values(array_filter( $lines, array($this,'acl_str')));
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
			return;
		}
	}

	// Нахождение элемента массива, в который засунули файл
	public function getArrayOfItems($id, $pid='') {

		$id	= is_numeric($pid) ? $pid : $id;

		$item 	 = $this->fileACL2Array($this->squidacl_fname)[$id]; // строка в файле
		$data = preg_split('/\\t/', $item);  // данные
		
		if( $pid ) {   // работаем с набором значений ACL
			$data = explode(' ' ,$data[3]);	 	   // массив данных
		}

		return $data;
    }	
    
    protected function DTPropAddToArray($row,$tab,$class) {

		$returnData = array();
		if(! count($row)) {	return $returnData;	}

		foreach($row as $k => $item) {
			$returnData[] = array( $item,
								'DT_RowClass' => $class,
								'DT_RowId'    => 'tab-'.$tab.'-'.$k
								);
		}
		return $returnData;
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
