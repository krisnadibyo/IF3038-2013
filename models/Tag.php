<?php
class Task_Tag extends Model
{
    protected static $table = 'task_tag';

    protected $id = null;

    protected $task_id = null;
    protected $tag_id = null;
}

class Tag extends Model
{
    protected static $table = 'tag';

    protected $id = null;
    protected $name = null;

    public static function load()
    {
        parent::load();
        Task_Tag::load();
    }

    public static function getAllByTaskId($task_id, $returnObjectArray=true)
    {
        $tags = self::getAll(array(
            'select' => array('tag.id', 'tag.name'),
            'from' => 'tag LEFT JOIN task_tag ON (task_tag.tag_id = tag.id)',
            'where'=> array(
                array('task_id', '=', $task_id)
            )
        ), $returnObjectArray);

        return $tags;
    }
}
