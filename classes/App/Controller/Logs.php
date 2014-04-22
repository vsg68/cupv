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
#						array_push($query,array( $this->pixie->db->expr('LOCATE("qmgr",X.SysLogTag)'),'>', 0));
						array_push($query,array( $this->pixie->db->expr('X.Message REGEXP "^from=<.*'.$filter.'"'),'=', 1));
					}
					else {
#						array_push($query,array( $this->pixie->db->expr('LOCATE("pipe",X.SysLogTag)'),'>', 0));
						array_push($query,array( $this->pixie->db->expr('X.Message REGEXP "^to=<.*'.$filter.'"'),'=', 1));
					}
				}
				else // С этим запросом отрабатывает быстрее
					array_push($query,array($this->pixie->db->expr('LOCATE("qmgr",X.SysLogTag)'),'>', 0));


				$answer = $this->pixie->db->query('select','logs')
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
							->order_by('receivedat')
							->order_by('msgid')
							->execute()
							->as_array();

				$values = count($answer) ? $this->DTPropAddToObject($answer, '', '') : array("ReceivedAt"=>"-","SysLogTag"=>"-","MSGID"=>"-","Message"=>"-");
				$this->response->body = json_encode($values) ;

			}
			catch (\Exception $e) {
				$view = $this->pixie->view('form_alert');
				$view->errorMsg = $e->getMessage();
				$this->response->body = $view->render();
				return;
			}

	}

	public function action_tail() {
			if( $this->permissions == $this::NONE_LEVEL )
				return $this->noperm();

			//~ if( ! isset( $this->request->get('id')) )
				//~ return false;

			$id = $this->request->get('id');

			try {
				if( $id == '0') {
					$values = $this->pixie->orm->get('maillog')->order_by('id', 'desc')->limit(1)->find();
					$id = $values->ID - 1;
				}

				$answer = $this->pixie->orm->get('maillog')->where('ID','>', $id)->find_all()->as_array(true);

				$this->response->body = json_encode($answer) ;

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
