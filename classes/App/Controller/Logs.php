<?php
/*

 */
namespace App\Controller;

class Logs extends \App\Page {

    public function action_view() {

			if( $this->permissions == $this::NONE_LEVEL )
				return $this->noperm();


			$this->view->subview 		= 'logs';

			$this->view->script_file = '<script type="text/javascript" src="/js/logs.js"></script>';
			$this->view->css_file 	 = '<link rel="stylesheet" href="/css/logs.css" type="text/css" />';

			$this->response->body	= $this->view->render();
    }


	public function action_show() {

			if( $this->permissions == $this::NONE_LEVEL )
							return $this->noperm();


			$view 	= $this->pixie->view('logs_view');
			$query	= array();

			$params = $this->request->post();

			$time_start = $params['start_date']." ".$params['start_time'].":00";
			$time_stop  = $params['stop_date']." ".$params['stop_time'].":00";
			$server		= $params['server'];
			$filter 	= $params['fltr'];
			$direction  = $params['direction']; //0-To 1-From


			array_push($query,array('X.ReceivedAt','>',$time_start));
			array_push($query,array('X.ReceivedAt','<',$time_stop));
			array_push($query,array('A.ReceivedAt','>',$time_start));
			array_push($query,array('A.ReceivedAt','<',$time_stop));
			array_push($query,array($this->pixie->db->expr('LOCATE("cleanup",Y.SysLogTag)'),'>', 0));


			if( $server )
				array_push($query,array('X.FromHost','=', $server));

			if( $filter ) {

				if( $direction ) { // From
					array_push($query,array( $this->pixie->db->expr('LOCATE("qmgr",X.SysLogTag)'),'>', 0));
					array_push($query,array( $this->pixie->db->expr('X.Message REGEXP "'.$filter.'"'),'=', 1));
				}
				else {
					array_push($query,array( $this->pixie->db->expr('LOCATE("pipe",X.SysLogTag)'),'>', 0));
					array_push($query,array( $this->pixie->db->expr('X.Message REGEXP "'.$filter.'"'),'=', 1));
				}
			}
			else // С этим запросом отрабатывает быстрее
				array_push($query,array($this->pixie->db->expr('LOCATE("qmgr",X.SysLogTag)'),'>', 0));

			$view->messages = $this->pixie->db
											->query('select','logs')
											->fields($this->pixie->db->expr('DISTINCT
																			A.ReceivedAt AS ReceivedAt,
																			REPLACE(A.SysLogTag,"postfix\/","") AS SysLogTag,
																			A.MSGID AS MSGID,
																			A.Message AS Message'
											))
											->table('maillog','X')
											->JOIN(array('maillog','Y'),array('X.MSGID','Y.MSGID'),'LEFT')
											->JOIN(array('maillog','Z'),array('Y.message','Z.message'),'LEFT')
											->JOIN(array('maillog','A'),array('A.MSGID','Z.MSGID'),'LEFT')
											->where($query)
											->execute();

			$this->response->body = $view->render() ;

	}


}
?>
