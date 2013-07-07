<?php
/*

 */
namespace App\Controller;

class Logs extends \PHPixie\Controller {

   private $logmsg;


    public function action_view() {

        $view = $this->pixie->view('main');

		$view->subview 		= 'logs_main';

		$view->script_file	= '<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>'.
								'<script type="text/javascript" src="/logs.js"></script>';
		$view->css_file 	= '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />'.
								'<link rel="stylesheet" href="/logs.css" type="text/css" />';

        $this->response->body	= $view->render();
    }


	public function action_single() {

		$view 		= $this->pixie->view('groups_view');
		$view->log 	= isset($this->logmsg) ?  $this->logmsg : '';


		if( ! $this->request->get('name') )
			return;

		if( ! isset($this->domain_id) )
			$this->domain_id = $this->request->get('name');

		$domain = $this->pixie->db
								->query('select')->table('domains')
								->where('domain_id', $this->domain_id)
								->execute()
								->current();

		//Если ответ пустой
		if( ! count($domain) )
			return "<strong>Домена с ID ".$this->domain_id." не существует.</strong>";

		$view->domain = $domain;

		// Собираем алиасы домена
		$view->aliases = $this->pixie->db
									->query('select')->table('domains')
									->where('delivery_to', $domain->domain_name)
									->execute();

		// Редактирование
		if( ! $this->request->get('act') )
			return $view->render();

		$this->response->body = $view->render();
	}


	public function action_show() {

        if ($this->request->method == 'POST') {

			$view 	= $this->pixie->view('logs_view');
			$query	= array();

			$params = $this->request->post();

			$time_start = $params['start_date']." ".$params['start_time'].":00";
			$time_stop  = $params['stop_date']." ".$params['stop_time'].":00";
			$server		= $params['server'];
			$filter 	= $params['fltr'];
			$direction  = $params['direction']; //0-To 1-From

			$tmpl_From 	= '/([A-F0-9]+):\s+from=<'.$filter.'/';
			$tmpl_To  = '/([A-F0-9]+):\s+to=<'.$filter.'/';
			//$fromServer = '';
			$all_messages = '';

			array_push($query,array('ReceivedAt','>',$time_start));
			array_push($query,array('ReceivedAt','<',$time_stop));
			array_push($query,array($this->pixie->db->expr('LOCATE("cleanup",Y.SysLogTag)'),'>', 0));


			if( $server )
				array_push($query,array('FromHost','=', $server));

			if( $filter ) {

				if( $direction ) { // From
					array_push($query,array( $this->pixie->db->expr('LOCATE("qmgr",maillogs.SysLogTag)'),'>', 0));
					array_push($query,array( $this->pixie->db->expr('maillogs.Message REGEXP "'.$filter.'"'),'=', 1));
					//$tmpl = $tmpl_From;
				}
				else {
					array_push($query,array( $this->pixie->db->expr('LOCATE("pipe",maillogs.SysLogTag)'),'>', 0));
					array_push($query,array( $this->pixie->db->expr('maillogs.Message REGEXP "'.$filter.'"'),'=', 1));
					//$tmpl = $tmpl_To;
				}
			}
			else // С этим запросом отрабатывает быстрее
				array_push($query,array($this->pixie->db->expr('LOCATE("qmgr",maillogs.SysLogTag)'),'>', 0));

			//$entries_addr = array();

			$view->messages = $this->pixie->db
											->query('select','logs')->table("maillogs")
											->fields(
													$this->pixie->db->expr("CAST(A.ReceivedAt as TIME) AS ReceivedAt"),
													$this->pixie->db->expr("REPLACE(A.SysLogTag,'postfix\/','') AS SysLogTag"),
													$this->pixie->db->expr('A.MSGID AS MSGID'),
													$this->pixie->db->expr('A.Message AS Message')
											)
											->JOIN(array('maillogs','Y'),array('maillogs.MSGID','Y.MSGID'),'LEFT')
											->JOIN(array('maillogs','Z'),array('Y.message','Z.message'),'LEFT')
											->JOIN(array('maillogs','A'),array('A.MSGID','Z.MSGID'),'LEFT')
											->where($query)
											->execute();



			//$this->response->body = ( $all_messages ) ? $all_messages : '
			$this->response->body = $view->render() ;

		}

	}


}
?>
