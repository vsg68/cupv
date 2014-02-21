<?php

namespace App\Controller;

class SquidACL extends \App\Page {

	public function action_showTable() {

		try {
			$fname = '/home/vsg/squid.acl.tmp';
			$acls = array();
			$handle = fopen($fname, "r");

			while (($line = fgets($handle)) !== false) {
				// берем построчно файл и закидываем в массив.
				if( preg_match('/^acl\s+/', $line) ) {
					$matches = preg_split('/\\t/', trim($line));
					$acls['aaData'][] = array('acl'		=> $matches[0],
												'name'		=> $matches[1],
												'type'		=> $matches[2],
												'data'		=> $matches[3],
												'comment'	=> $this->getVar($matches[4])
												);
				}			
			}
			
			fclose($handle);
			$this->response->body = json_encode($acls);
		}
		catch (\Exception $e) {
			$view = $this->pixie->view('form_alert');
			$view->errorMsg = $e->getMessage();
			$this->response->body = $view->render();
		}
	}
 }

?>
