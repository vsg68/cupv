<?php

namespace App\Controller;

class Roles extends \App\Page {


   public function action_showTable(){
        $entries = $this->pixie->orm->get('roles')->find_all()->as_array(true);
        $this->response->body = json_encode($entries);
   }

   public function action_getRoles(){
        $entries = $this->pixie->db->query('select','admin')
                                   ->fields($this->pixie->db->expr('id AS rid, name AS id, name AS value'))
                                   ->table('roles')
                                   ->where('active', 1)
                                   ->execute()->as_array();

        $this->response->body = json_encode($entries);
   }

   public function action_select(){
 
        $params = $this->request->get();

        $entries = $this->pixie->db->query('select','admin')
            ->fields($this->pixie->db->expr("L.id AS id, S.name as sectname, SL.id AS slid, SL.name AS slname, SL.slevel "))
            ->table('rights','L')
            ->join(array('sections','S'),array('L.section_id','S.id'))
            ->join(array('slevels','SL'),array('L.slevel_id','SL.id'))
            ->where("L.role_id", $params['id'])
            ->execute()->as_array();

        $this->response->body = json_encode($entries);
    }

    public function action_delEntry() {

        if( ! $params = $this->request->post() )
            return;

        try {
            // delete_all() -- убиваем не доставая
            $this->pixie->orm->get('roles')->where( 'id', $params['id'])->delete_all();

            // Если есть связанные страницы - обнуляем связь (section_id)
            $this->pixie->orm->get('rights')->where('role_id', $params['id'])->delete_all();         // убили саму таблицу
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }
    
    public function action_save() {

        if( ! $params = $this->request->post() )
            return false;

        try {
            $is_update = $params['is_new'] ? false : true;
            unset( $params['is_new']);

            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->orm->get("roles")->values($params, $is_update)->save();

            if( $is_update )  // Выход, если было обновление
                return true;

//          Добавляем связи созданного раздела с ролями
            $sections = $this->pixie->orm->get("sections")->find_all()->as_array(true);
            $slevel   = $this->pixie->orm->get("slevels")->where('name','NONE')->find();
//          Новая запись для всех разделов
            foreach($sections as $section) {
                $this->pixie->orm->get("rights")->values(
                                array(
                                    "section_id" => $section->id,
                                    "slevel_id" =>  $slevel->id,
                                    "role_id"    => $params['id']
                                ))->save();
            }
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

   public function action_saveRights() {

        if( ! $params = $this->request->post() )
            return false;

        try {
            // Если в запрос поместить true -  предполагается UPDATE
            $this->pixie->db->query('update','admin')
                            ->table('rights')
                            ->data(array('slevel_id' => $params['slid']))
                            ->where('id', $params['id'])
                            ->execute();
            
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }

    public function action_getSlevel() {
        $entries = $this->pixie->db->query('select','admin')
                                    ->table('slevels')
                                    ->fields($this->pixie->db->expr("name as id, name as value, id AS slid"))->execute()->as_array();
        $this->response->body = json_encode($entries);
    }
}
?>
