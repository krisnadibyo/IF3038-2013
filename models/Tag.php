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

    public static function getOneByName($name)
    {
        return self::getOne(array(
            'where' => array(
                array('name', '=', $name),
            ),
        ));
    }

    public function validate($boolReturn=false)
    {
        $error = array();

        if (self::getOneByName($this->name) != null) {
            $error['name'][] = 'Tag with the same name exist';
        }

        if (preg_match('/,/', $this->name)) {
            $error['name'][] = 'Tag name should not contain any commas';
        }

        if ($boolReturn) {
            return $error === array() ? true : false;
        }

        return $error;
    }
}
