<?php

namespace App\Controller;

class Domains extends \App\Page {


    public function action_delEntry() {

        if( ! $params = $this->request->post() )
            return;

        try {
            $this->pixie->orm->get("domains")->where('id',$params['id'])->delete_all();
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

    public function action_showTable(){

        $result = $this->pixie->orm->get('domains')->find_all()->as_array(true);
       
        foreach($result as $str) {
             $str->relay_notcheckusers = ! $str->relay_notcheckusers;
         }

        $this->response->body = json_encode($result);
    }


    public function action_save() {

        if( ! $params = $this->request->post() )
            return false;

        try {
            $params['relay_notcheckusers'] = ! $params['relay_notcheckusers'];
            $is_update = $params['is_new'] ? false : true;
            unset( $params['is_new']);

            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->orm->get("domains")->values($params, $is_update)->save();
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }
}
?>
