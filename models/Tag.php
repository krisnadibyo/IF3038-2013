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

    public static function assign($tag_ids, $task_id)
    {
        $sql = "INSERT IGNORE INTO task_tag (task_id, tag_id) VALUES (:task_id, :tag_id)";

        if (!is_array($tag_ids)) {
            self::db()->executeSql($sql, array(
                'task_id' => $task_id,
                'tag_id' => $tag_ids,
            ));
            return;
        }
        
        foreach ($tag_ids as $tag_id) {
            self::db()->executeSql($sql, array(
                'task_id' => $task_id,
                'tag_id' => $tag_id,
            ));
        }
    }

    public static function reassign($tag_ids, $task_id)
    {
        Task_Tag::deleteWhere('task_id = :task_id', array('task_id' => $task_id));
        self::assign($tag_ids, $task_id);
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
