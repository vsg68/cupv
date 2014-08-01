<?php

namespace App\Controller;

class Groups extends \App\Page {

    public function action_getGroupsList(){

        $result = $this->pixie->db->query('select')
                                    ->table('groups')
                                    ->fields($this->pixie->db->expr("name AS id, id AS group_id, name AS value"))
                                    ->where("active",1)
                                    ->execute()->as_array();

        $this->response->body = json_encode($result);
    }
//    public function action_showTree(){
//
//        $entries = $this->pixie->db->query('select')
//                                    ->fields($this->pixie->db->expr("G.id, G.name, G.active, concat(G.id ,U.id) AS uid, U.mailbox AS value"))
//                                    ->table('groups', 'G')
//                                    ->join(array('lists','L'),array('L.group_id','G.id'))
//                                    ->join(array('users','U'),array('L.user_id','U.id'))
//                                    ->order_by("G.id")
//                                    ->execute()->as_array();
//
//        $this->response->body = json_encode($entries);
//
//    }
    public function action_showTree(){

        $entries = $this->pixie->db->query('select')
                                    ->fields($this->pixie->db->expr("G.id AS id, G.name AS value, G.active, U.id AS uid, U.mailbox as mailbox, 1 AS open"))
                                    ->table('groups', 'G')
                                    ->join(array('lists','L'),array('L.group_id','G.id'))
                                    ->join(array('users','U'),array('L.user_id','U.id'))
                                    ->order_by("G.id")
                                    ->execute()->as_array();

        $result = array();
        foreach( $entries as $entry) {

            $data = array("id" => $entry->uid, "value" => $entry->mailbox, "user_id" => $entry->uid,);
            $uid = $entry->uid;

            unset($entry->uid, $entry->mailbox);

            if( ! isset( $result[ $entry->id ]->data ) ) {
                $result[ $entry->id ] = $entry;
                if( isset($uid) )
                    $result[ $entry->id ]->data = array();
            }
            if( isset($uid) )
                array_push( $result[ $entry->id ]->data, $data );
        }

        $this->response->body = json_encode(array_values($result));
    }

    public function action_save(){
        if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

        if( ! $params = $this->request->post() )
            return false;

        try {
            $is_update =  $params['is_new'] ? false : true;
            unset( $params['is_new'],$params['active'], $params["name"] );

            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->orm->get("lists")->values($params, $is_update)->save();
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

    public function action_savegroup(){
        if( $this->permissions != $this::WRITE_LEVEL )
			return $this->noperm();

        if( ! $params = $this->request->post() )
            return false;

        try {
            // Если в запрос поместить true -  предполагается UPDATE
            $is_update =  (isset($params['is_new']) && $params['is_new'] )? false : true;

            if( $params['$parent'] )
                $this->pixie->orm->get("lists")->values(array('id'       => $params['id'],
                                                              'group_id' => $params['$parent'],
                                                              'user_id'  => $params['user_id'],
                                                            ),$is_update)->save();
            else
                $this->pixie->orm->get("groups")->values(array('id'     => $params['id'],
                                                               'name'   => $params['value'],
                                                               'active' => $params['active']
                                                            ),$is_update)->save();
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

    public function action_delEntry()
    {
        if ($this->permissions != $this::WRITE_LEVEL)
            return $this->noperm();

        if (!$params = $this->request->post())
            return false;

        try {
            if ( !isset($params['group_id']) ) {
                $this->pixie->orm->get("lists")->where("id", $params["id"])   // сюда приходит запрос из раздела пользователей
                    ->delete_all();
            }
            elseif ( $params['group_id'] == 0 ) { // удаление группы
                // запрос на удаление группы
                $this->pixie->orm->get("lists")
                    ->where("group_id", $params["id"])
                    ->delete_all();

                $this->pixie->orm->get("groups")
                    ->where("id", $params["id"])
                    ->delete_all();
            }
            elseif( $this->getVar($params['group_id']) > 0 ) {  // удаление пользователя
                $this->pixie->orm->get("lists")->where("group_id", $params['group_id'])
                    ->where("user_id", $params["id"])
                    ->delete_all();
            }

        } catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }
}
?>
