<?php

namespace App\Controller;

class SquidACL extends \App\Page {

	protected $squidacl_fname;

	public function before() {
		$this->squidacl_fname = '/home/vsg/squid.acl.tmp';
		if(! file_exists($this->squidacl_fname) ) {
			throw new \Exception("Файл {$this->squidacl_fname} не найден");
		}
		\App\Page::before();
	}

	public function action_showTable() {
		try{
			$lines = $this->fileACL2Array( $this->squidacl_fname );

			foreach( $lines  as $key => $line ) {
				$matches = preg_split('/\\t/', $line);
				$acls['aaData'][$key] = array('name'	=> $matches[1],
											'type'		=> $matches[2],
											'comment'	=> ltrim($this->getVar($matches[4]), '#'),
											'active'	=> ( preg_match('/^#/', $matches[0]) ? 0 : 1 ),
											'DT_RowId'	=> 'tab-'.$this->ctrl.'-'.$key
											);
			}

			$this->response->body = json_encode($acls);
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
			return;
		}
	}

	public function action_showEditForm() {

		if( $this->permissions == $this::NONE_LEVEL )
			return $this->noperm();

		if( ! $tab = $this->request->post('t') )
			return;

		$view 		= $this->pixie->view('form_'.$tab);
		$view->tab  = $tab;
		$this->_id 	= $this->request->param('id');
		$view->id 	= $this->_id;
		$pid 		= $this->request->post('init');

		if ($tab != 'squidacl') {
			$view->pid	= $pid;
		}

		$line = $this->getArrayOfItems($this->_id, $pid);

		// вставляем активность вместо данных
		if( $tab == 'squidacl') {
			$line[3] = preg_match('/^#/', $line[0]) ? 0 : 1;
		}
		$view->data = ($tab == 'squidacl') ? $line : (count($line) ? $line[$this->_id] : '');

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

    public function action_edit() {

		if( ! $params = $this->request->post() )
			return;
//~
		//~ if( $this->permissions != $this::WRITE_LEVEL )
			//~ return $this->noperm();

		try {
			$pid 	= $this->getVar($params['pid']);
			$id  	= $params['id'];
			$tab 	= $params['tab'];
			$i  	= isset($pid) ? $pid : $id;

			$params['active'] = $this->getVar($params['active'],0);

			if( $pid ) {
				 unset($params['active'], $params['pid']);
			}
			unset($params['tab'],$params['id']);

			// Работаем с массивом
			$fileArr = $this->fileACL2Array($this->squidacl_fname);

			if( is_numeric($pid) ) { // работаем с данными строки
				preg_split('/\\t/', $fileArr[$pid], $lines);
				$data 	= explode(' ', $lines[3]);
				$id 	= preg_match('/00$/', $id) ? count($data) : $id;
				$data[$id]	= $params; //['data'];
				$lines[3] 	= implode(" ", $data);
			}
			else {
				$id = preg_match('/00$/', $id) ? count($fileArr) : $id;
				$lines = array(	($params['active'] ? '#acl' : 'acl'),
								 $params['name'],
								 $params['type'],
								 null,
								 $params['comment']
								 );
				$i = $id;
			}

			$fileArr[$i] = implode("\t", $lines);	// Это уже можно сохранять
			// Сохранение
			// ,,,,.....


			$returnData 				= $params;
			$returnData['DT_RowClass']	= $pid ? 'gradeA' : '';
			$returnData['DT_RowId'] 	= 'tab-'.$tab.'-'.$id;

			$this->response->body = json_encode($returnData);
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

	protected function fileACL2Array($fname) {

		$lines = file($fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		return array_values(array_filter( $lines, array($this,'acl_str')));
	}

	// Нахождение элемента массива, в который засунули файл
	public function getArrayOfItems($id, $pid='') {

		$i	= is_numeric($pid) ? $pid : $id;
		$data = array();

		// Если новая запись не новая
		if( ! preg_match('/00$/', $id) ) {
			$item 	 = $this->fileACL2Array($this->squidacl_fname)[$i]; // строка в файле
			$data = preg_split('/\\t/', $item);  // данные

			if( $pid ) {   // работаем с набором значений ACL
				$data = explode(' ' ,$data[3]);	 	   // массив данных
			}
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
