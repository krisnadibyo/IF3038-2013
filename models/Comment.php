<?php
class Comment extends Model
{
    protected static $table = 'comment';

    protected $id = null;
    protected $task_id = null;
    protected $content = null;

    public static function getAllByTaskId($task_id, $returnObjectArray=true)
    {
        return self::getAll(array(
            'where' => array(
                array('task_id', '=', $task_id),
            ),
        ), $returnObjectArray);
    }
}
