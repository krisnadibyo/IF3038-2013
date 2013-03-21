<?php
class Category extends Model
{
    protected static $table = 'tbl_category';

    protected $id = null;
    protected $name = null;
    protected $user_id = null;

    public static function getOneByName($name, $userId) {
        return self::getOne(array(
            'where' => array(
                array('name', '=', $name),
                'AND',
                array('user_id', '=', $userId),
            ),
        ));
    }
}
