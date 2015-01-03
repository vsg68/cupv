<?php

namespace App\Controller;

class Nets extends \App\Page {


    public function action_delEntry() {

        if( ! $params = $this->request->post() )
            return;

        try {
            $this->pixie->orm->get("nets")->where('id',$params['id'])->delete_all();
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

    public function action_showTable(){

        $result = $this->pixie->orm->get('nets')->find_all()->as_array(true);
        $this->response->body = json_encode($result);
    }

    public function action_getList(){

            $result = $this->pixie->db->query('select',"admin")
                                    ->fields($this->pixie->db->expr("concat(net,'/',mask) AS value"))
                                    ->table("nets")
                                    ->where("active",1)
                                    ->execute()->as_array();

            $this->response->body = json_encode($result);
        }


    public function action_save() {

        if( ! $params = $this->request->post() )
            return false;

        try {
            $is_update = $params['is_new'] ? false : true;
            unset( $params['is_new']);

            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->orm->get("nets")->values($params, $is_update)->save();
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }
}
?>
