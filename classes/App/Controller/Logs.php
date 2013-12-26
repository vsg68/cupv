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

			$query = $values = array();
			$returnData = array();

			$params = $this->request->get();

			try {
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


				$answer = $this->pixie->db->query('select','logs')
											->fields($this->pixie->db->expr('DISTINCT
																			A.ReceivedAt AS receivedat,
																			REPLACE(A.SysLogTag,"postfix\/","") AS syslogtag,
																			A.MSGID AS msgid,
																			A.Message AS message'
											))
											->table('maillog','X')
											->JOIN(array('maillog','Y'),array('X.MSGID','Y.MSGID'),'LEFT')
											->JOIN(array('maillog','Z'),array('Y.message','Z.message'),'LEFT')
											->JOIN(array('maillog','A'),array('A.MSGID','Z.MSGID'),'LEFT')
											->where($query)
											->order_by('receivedat')
											->order_by('msgid')
											->execute()
											->as_array();

				$values = count($answer) ? $this->DTPropAddToObject($answer, '', '') : array("receivedat"=>"-","syslogtag"=>"-","msgid"=>"-","message"=>"-");
				$this->response->body = json_encode($values) ;
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
