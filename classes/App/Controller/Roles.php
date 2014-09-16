<?php

namespace App\Controller;

class Roles extends \App\Page {

    public function action_save(){

        if( ! $params = $this->request->post() )
            return false;

        try {
            $is_update =  $params['is_new'] ? false : true;
            unset( $params['is_new'],$params['active'], $params["name"] );

            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->orm->get("lists")->values($params, $is_update)->save();

            if( $is_update )  // Выход, если было обновление
                return true;

//          Добавляем связи созданного раздела с ролями
            $sections = $this->pixie->orm->get("sections")->find_all();
//               Новая запись для всех разделов
            $rights_entry = $this->pixie->orm->get("rights");
    print_r($sections);exit;
            foreach($sections as $section){
                $rights_entry->id = 'now()';
                $rights_entry->section_id = $section->id;
                $rights_entry->role_id = $params['id'];
                $rights_entry->save();
            }
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }


    public function action_showTree(){


        // $entries = $this->pixie->db->query('select','admin')
        //     ->fields($this->pixie->db->expr("R.id AS id, R.name AS value, R.active, S.id as sid, S.name as sectname,
        //             ( CASE SL.slevel
        //                  WHEN  0 THEN 'NONE'
        //                  WHEN  1 THEN 'READ'
        //                  WHEN  2 THEN 'WRITE'
        //             END ) AS image,
        //           1 AS open"))
        //     ->table('roles', 'R')
        //     ->join(array('rights','L'),array('L.role_id','R.id'))
        //     ->join(array('sections','S'),array('L.section_id','S.id'))
        //     ->join(array('slevels','SL'),array('L.slevel_id','SL.id'))
        //     ->order_by("R.id")
        //     ->execute()->as_array();

        $entries = $this->pixie->db->query('select','admin')
            ->fields($this->pixie->db->expr("R.id AS rid, R.name AS rname, R.active, S.id as sid, S.name as sectname, SL.id AS slid, SL.slevel AS slevel"))
            ->table('roles', 'R')
            ->join(array('rights','L'),array('L.role_id','R.id'))
            ->join(array('sections','S'),array('L.section_id','S.id'))
            ->join(array('slevels','SL'),array('L.slevel_id','SL.id'))
            ->order_by("R.id")
            ->execute()->as_array();

        $result = array();
        
//         foreach( $entries as $entry) {
// //  slevel в качестве id не должен быть одинаковым
//             $data = array("id" => $entry->id."_".$entry->sid, "value" => $entry->sectname, "image" => $entry->image);
//             $uid = $entry->sid;

// //            unset( $entry->sectname, $entry->slevel);
//             unset($entry->sid, $entry->sectname);

//             if( ! isset( $result[ $entry->id ]->data ) ) {
//                 $result[ $entry->id ] = $entry;
//                 if( isset($uid) )
//                     $result[ $entry->id ]->data = array();
//             }
//             if( isset($uid) )
//                 array_push( $result[ $entry->id ]->data, $data );
//         }
//        print_r($result); exit;
        $this->response->body = json_encode(array_values($result));
    }

}
?>
