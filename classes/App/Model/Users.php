<?php

namespace App\Model;
class Users extends \PHPixie\ORM\Model{

   /* нужно прописывать поскольку класс обозначается от таблицы (мн.ч)
    *  но у нас передаются таблицы, т.е. таблица должда называться так же, как класс
    *  для правильной обработки
    */
    public $table = 'users';

//  предполагается, что в таблице controllers есть поле section_id
    protected $has_many = array(
						'groups'=> array(
									'model'		 =>'groups',
									'through'	 =>'lists',
									'key'		 =>'mailbox',
									'foreign_key'=>'group_id'
									));
/*
 * Эта связь нужна если нам нужно в цикле вытягивать значения и из
 * таблицы контроллеров и из разделов.
 */
	protected $has_one = array('lists' => array(
											'model'=>'lists',
											'key'=>'user_id'));

/*
 *  !!!!!!!!!
*/
	public function get($column) {

		//~ if($column == 'active' || $column == 'imap_enable' ) {
			//~ return isset($this->name)  ? $this->name : 0;
//~
		//~ }
	}
}
