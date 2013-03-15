<?php
class Task extends Model
{
    protected static $table = 'task';

    protected $id = null;
    protected $name = null;
    protected $attachment = null;
    protected $deadline = null;

    // Foreign Keys:
    protected $user_id = null;
    protected $assignee_id = null;
    protected $category_id = null;
}
