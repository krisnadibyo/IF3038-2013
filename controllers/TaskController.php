<?php
class TaskController extends Controller
{
    protected function init()
    {
        foreach (array('User', 'Category', 'Task', 'Tag', 'Comment') as $model) {
            App::loadModel($model);
        }

        Session::init();
        if (!Session::loggedIn()) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }

        $this->userId = Session::get('userid');
    }

    // GET /task/all/[<complete>]
    public function all($complete=false)
    {
        $tasks = Task::getAll();

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $this->_complete($task);
            }
        }

        return $this->response->renderJson($tasks, true);
    }

    // GET /task/search_name/<name>/[<complete>]
    public function search_name($name='', $complete=false)
    {
        $tasks = Task::getAll(array(
            'where' => array(
                array('name', 'LIKE', '%' . $name . '%'),
                'AND (',
                array('user_id', '=', $this->userId),
                'OR',
                array('assignee_id', '=', $this->userId),
                ')',
            ),
        ));

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $this->_complete($task);
            }
        }

        return $this->response->renderJson($tasks, true);
    }

    // Autocomplete hint
    // GET /task/hint/<name>
    public function hint($name='')
    {
        $hints = array();

        $tasks = Task::getAll(array(
            'where' => array(
                array('name', 'LIKE', $name . '%'),
                'AND (',
                array('user_id', '=', $this->userId),
                'OR',
                array('assignee_id', '=', $this->userId),
                ')',
            ),
        ));

        if ($name != '' && $tasks != null) {
            foreach($tasks as $task) {
                $hints[] = $task->get_name();
            }
        }

        return $this->response->renderJson($hints);
    }

    // GET /task/category/<category_name>/[<complete>]
    public function category($category='Uncategorized', $complete=false)
    {
        $tasks = Task::getByCategoryName($category, $this->userId);

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $this->_complete($task);
            }
        }

        return $this->response->renderJson($tasks, true);
    }

    // GET /task/user/<username>/[<complete>]
    public function user($username='', $complete=false)
    {
        if ($username === '') {
            $username = Session::get('username');
            $complete = true;
        }

        $tasks = Task::getByUser($username);

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $this->_complete($task);
            }
        }

        return $this->response->renderJson($tasks, true);
    }

    // GET /task/assignee/<assignee>/[<complete>]
    public function assignee($assignee='', $complete=false)
    {
        if ($assignee === '') {
            $assignee = Session::get('username');
            $complete = true;
        }
        $tasks = Task::getByAssignee($assignee);

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $this->_complete($task);
            }
        }

        return $this->response->renderJson($tasks, true);
    }

    // GET /task/tag/<tag_name>/[<complete>]
    public function tag($tagname='', $complete=false)
    {
        $tasks = Task::getByTag($tagname, $this->userId);

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $this->_complete($task);
            }
        }

        return $this->response->renderJson($tasks, true);
    }


    // GET /task/get/<id>/[<complete>]
    public function get($id=0, $complete=false)
    {
        if (!$task = Task::getOneById($id)) {
            return $this->response->nullJson();
        }

        if ($complete) {
            $this->_complete($task);
        }

        return $this->response->renderJson($task->toArray());
    }

    // POST /task/done/<task_id>
    public function done($id=0)
    {
        if (!$this->_isPOST() || !$task = Task::getOneById($id)) {
            return $this->response->nullJson();
        }

        $task->set_status(1);
        $task->save();
        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /task/done/<task_id>
    public function undone($id=0)
    {
        if (!$this->_isPOST() || !$task = Task::getOneById($id)) {
            return $this->response->nullJson();
        }

        $task->set_status(0);
        $task->save();
        return $this->response->renderJson(array('status' => 'success'));
    }


    // POST /task/create + JSON data
    public function create()
    {
        // make sure the request method is POST and sending data
        // and task data isn't bad
        $taskData = null;
        if (!$this->_isPOSTandHasData($taskData)) {
            return $this->response->nullJson();
        }

        $task = new Task($taskData);
        if (($validation = $task->validate()) !== array()) {
            return $this->response->renderJson($validation);
        }

        $task->save_new(false);
        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /task/edit/<id> + JSON data
    public function edit($id=0)
    {
        $taskData = null;
        if (!$this->_isPOSTandHasData($taskData)) {
            return $this->response->nullJson();
        }

        if (!$task = Task::getOneById($id)) {
            return $this->response->nullJson();
        }

        foreach ($taskData as $col => $val) {
            $task->set($col, $val);
        }

        if (($validation = $task->validate()) !== array()) {
            return $this->response->renderJson($validation);
        }

        $task->save(false);
        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /task/delete/<id>
    public function delete($id=0)
    {
        if (!$this->_isPOST() || !$task = Task::getOneById($id)) {
            return $this->response->nullJson();
        }

        $task->delete();
        return $this->response->renderJson(array('status' => 'success'));
    }

    // Private functions
    public function _complete(&$task) {
            $task->user = $task->get_user()->get_name();
            $assignee = $task->get_assignee();
            if ($assignee != null) {
                $task->assignee = $assignee->get_name();
            }
            $task->category = $task->get_category()->get_name();
            $task->tags = $task->get_tags(true);
            $task->comments = $task->get_comments(true);
    }
}
