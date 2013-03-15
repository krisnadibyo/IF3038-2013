<?php
class Comment extends Model
{
    protected static $table = 'comment';

    protected $id = null;
    protected $task_id = null;
    protected $content = null;
}
