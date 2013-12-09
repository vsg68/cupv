<?php

namespace App\Model;
class Sections extends \PHPixie\ORM\Model{

    //Specify which connection to use
    public $connection = 'admin';
    /* нужно прописывать поскольку класс обозначается от таблицы (мн.ч)
    *  но у нас передаются таблицы, т.е. таблица должда называться так же, как класс
    *  для правильной обработки
    */
    public $table = 'sections';

//  предполагается, что в таблице controllers есть поле section_id
    protected $has_many = array(
						'controllers'=> array(
								'model'=>'controllers',
								'key'=>'section_id'
								));
/*
 * Эта связь нужна если нам нужно в цикле вытягивать значения и из
 * таблицы контроллеров и из разделов.
 */
	protected $has_one = array('ctrls' => array(
									'model'=>'controllers',
									'key'=>'section_id'));

}
