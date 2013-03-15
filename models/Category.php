<?php
class Category extends Model
{
    protected static $table = 'category';

    protected $id = null;
    protected $name = null;
    
    public static function getOneByName($name) {
        return self::getOne(array(
            'where' => array(
                array('name', '=', $name),
            ),
        ));
    }
}
