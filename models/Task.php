<?php
class Task extends Model
{
    protected static $table = 'tbl_task';

    protected $id = null;
    protected $name = null;
    protected $attachment = 'none';
    protected $deadline = null;
    protected $status = 0;

    // Foreign Keys:
    protected $user_id = null;
    protected $assignee_id = null;
    protected $category_id = null;

    public static function getByCategoryName($catName, $returnObjectArray=true)
    {
        return self::getAll(array(
            'select' => array('tbl_task.id', 'tbl_task.name',
                'tbl_task.attachment', 'tbl_task.deadline',
                'tbl_task.user_id', 'tbl_task.assignee_id',
                'tbl_task.category_id'),
            'from' => 'tbl_task LEFT JOIN tbl_category ON (tbl_task.category_id = tbl_category.id)',
            'where' => array(
                array('tbl_category.name', '=', $catName),
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

        // name
        if (!$this->name) {
            $error['name'][] = 'Required';
        } else {
            if (strlen($this->name) > 25) {
                $error['name'][] = 'Name length exceeded; Max. 25 chars';
            }
        }

        // deadline
        if (!$this->deadline) {
            $error['deadline'][] = 'Required';
        } else {
            $dateCorrect = preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $this->deadline, $m);
            if (!$dateCorrect) {
                $error['deadline'][] = 'Bad format; Must be in YYYY-MM-DD';
            } else {
                if ((int) $m[2] > 12 || (int) $m[2] < 1 || (int) $m[3] > 31 || (int) $m[3] < 1) {
                    $error['deadline'][] = 'Bad date';
                }
            }
        }

        // user
        if (!$this->user_id || !User::getOneById($this->user_id)) {
            $error['user'][] = 'User not found';
        }

        // assignee
        if ($this->assignee_id && !User::getOneById($this->assignee_id)) {
            $error['assignee'][] = 'User (assignee) not found';
        }

        // category
        if (!$this->category_id || !Category::getOneById($this->category_id)) {
            $error['category'][] = 'Category not found';
        }

        if ($boolReturn) {
            return $error === array() ? true : false;
        }

        return $error;
    }
}
