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
}
