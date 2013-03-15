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
    protected $fkobj = array();

    public function get_user()
    {
        return isset($this->fkobj['user']) ? $this->fkobj['user'] :
            $this->fkobj['user'] = User::getOneById($this->user_id);
    }

    public function get_assignee()
    {
        return isset($this->fkobj['assignee']) ? $this->fkobj['assignee'] :
            $this->fkobj['assignee'] = User::getOneById($this->assignee_id);
    }

    public function get_category()
    {
        return isset($this->fkobj['category']) ? $this->fkobj['category'] :
            $this->fkobj['category'] = Category::getOneById($this->category_id);
    }

    public function get_tags()
    {
        return isset($this->fkobj['tags']) ? $this->fkobj['tags'] :
            $this->fkobj['tags'] = Tag::getAllByTaskId($this->id);
    }

    public function set_category($cat_id)
    {
        $this->set_category_id($cat_id);
        unset($this->fkobj['category']);
    }

    public function set_assignee($assignee_id)
    {
        $this->set_assignee_id($assignee_id);
        unset($this->fkobj['assignee']);
    }
}
