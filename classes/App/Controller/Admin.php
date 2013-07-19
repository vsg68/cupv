<?php
/*

 */
namespace App\Controller;

class Admin extends \App\Page {

	 private $section_id;

     public function action_view() {

 		$this->view->script_file	= '<script type="text/javascript" src="/admin.js"></script>';
		$this->view->css_file 		= '<link rel="stylesheet" href="/admin.css" type="text/css" />';

		$this->view->subview = 'admin_main';

		$this->view->sections = $this->pixie->db
											->query('select')
											->table('sections')
											->execute();

		$this->view->sections_block = $this->get_single();

        $this->response->body	= $this->view->render();
    }

	public function get_single() {

		$view 		= $this->pixie->view('admin_view');
		$view->log 	= isset($this->logmsg) ?  $this->logmsg : '';

		if( ! $this->request->get('name') )
			//return "<img class='lb' src=/domains.png />";
			return;

		if( ! isset($this->section_id) )
			$this->section_id = $this->request->get('name');

		$section = $this->pixie->db
								->query('select')->table('sections')
								->where('id', $this->section_id)
								->execute()
								->current();

		$view->slevels = $this->pixie->db
											->query('select')
											->table('slevels')
											->execute();

		//Если ответ пустой
		if( ! count($section) )
			return "<strong>Раздела с ID ".$this->section_id." не существует.</strong>";

		$view->section = $section;

		// Собираем алиасы домена
		$view->controllers = $this->pixie->db
									->query('select')->table('controls')
									->where('section_id', $this->section_id)
									->execute();

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
    }




}
?>
