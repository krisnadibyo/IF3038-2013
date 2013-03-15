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

    public static function getByCategoryName($catName, $returnObjectArray=true)
    {
        return self::getAll(array(
            'select' => array('task.id', 'task.name', 'task.attachment', 'task.deadline', 'task.user_id', 'task.assignee_id', 'task.category_id'),
            'from' => 'task LEFT JOIN category ON (task.category_id = category.id)',
            'where' => array(
                array('category.name', '=', $catName),
            ),
        ), $returnObjectArray);
    }

    // Foreign Key objects, getters and setters
    public function get_user()
    {
        return User::getOneById($this->user_id);
    }

    public function get_assignee()
    {
        return User::getOneById($this->assignee_id);
    }

    public function get_category()
    {
        return Category::getOneById($this->category_id);
    }

    public function get_tags($returnArray=false)
    {
        return Tag::getAllByTaskId($this->id, !$returnArray);
    }
    
    public function get_comments($returnArray=false)
    {
        return Comment::getAllByTaskId($this->id, !$returnArray);
    }

    public function set_category($cat_id)
    {
        $this->set_category_id($cat_id);
    }

    public function set_assignee($assignee_id)
    {
        $this->set_assignee_id($assignee_id);
    }

    public function validate($boolReturn=false)
    {
        $error = array();
        if (!$this->name) {
            $error[] = json_encode(array('name' => 'Required'));
        }

        if (!$this->deadline) {
            $error[] = json_encode(array('deadline' => 'Required'));
        }

        if (!$this->user_id || !User::getOneById($this->user_id)) {
            $error[] = json_encode(array('user' => 'User not found'));
        }

        if ($this->assignee_id && !User::getOneById($this->assignee_id)) {
            $error[] = json_encode(array('assignee' => 'User not found'));
        }

        if (!$this->category_id || !Category::getOneById($this->category_id)) {
            $error[] = json_encode(array('category' => 'Category not found'));
        }
        
        if ($boolReturn) {
            return $error === array() ? true : false;
        }

        return $error;
    }
}
