<?php
/*

 */
namespace App\Controller;

class Admin extends \App\Page {

    public function action_delEntry() {

		if( ! $params = $this->request->post() )
			return;

		try {
			// delete_all() -- убиваем не доставая
			$this->pixie->orm->get('sections')->where( 'id', $params['id'])->delete_all();

			// Если есть связанные страницы - обнуляем связь (section_id)
	        $this->pixie->orm->get('rights')->where('section_id', $params['id'])->delete_all();			// убили саму таблицу
		}
		catch (\Exception $e) {
			$this->response->body = $e->getMessage();
		}
    }

	public function action_sections() {

		$entries = $this->pixie->orm->get('sections')->find_all()->as_array(true);
		$this->response->body = json_encode($entries);
	}

    protected function action_get_ctrl() {

        $fullpathfile_arr = glob(dirname(__FILE__).'/*.php');
        $names = preg_replace("!(.+)/([^/]+)(?:\.php)$!m","$2", $fullpathfile_arr);

        foreach( $names as $key => $name ) {
                $name = strtolower($name);
                $file_arr[] = array("id" => $name,"value" => $name);
        }

        $this->response->body = json_encode($file_arr);
    }

    public function action_save() {

        if( ! $params = $this->request->post() )
            return false;

        try {
            $is_update = $params['is_new'] ? false : true;
            unset( $params['is_new']);

            // Если в запрос поместить true -  предполагается UPDATE
           $this->pixie->orm->get("sections")->values($params, $is_update)->save();

            if( $is_update )  // Выход, если было обновление
                return true;

//          Добавляем связи созданного раздела с ролями
            $roles  = $this->pixie->orm->get("roles")->find_all()->as_array(true);
            $slevel = $this->pixie->orm->get("slevels")->where('name','NONE')->find();
//               Новая запись для всех разделов
            foreach($roles as $role){
                $this->pixie->orm->get("rights")->values(
                                array(
                                    "role_id"    => $role->id,
                                    "slevel_id"  => $slevel->id,
                                    "section_id" => $params['id']
                                ))->save();
            }
        }
        catch (\Exception $e) {
            $this->response->body = $e->getMessage();
        }
    }
}
?>
