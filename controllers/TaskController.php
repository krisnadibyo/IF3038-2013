<?php
class TaskController extends Controller
{
    protected function init()
    {
        foreach (array('User', 'Category', 'Task', 'Tag', 'Comment') as $model) {
            App::loadModel($model);
        }

        Session::init();
        if (!Session::loggedIn() && !$this->request->getParam('magicauth')) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }
    }

    // GET /task/all/[<complete>]
    public function all($complete=false)
    {
        $tasks = Task::getAll();

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $task->user = $task->get_user()->get_name();
                $task->category = $task->get_category()->get_name();
                $task->tags = $task->get_tags(true);
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
            ),
        ));

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $task->user = $task->get_user()->get_name();
                $task->category = $task->get_category()->get_name();
                $task->tags = $task->get_tags(true);
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
        $tasks = Task::getByCategoryName($category);

        if ($complete && $tasks != null) {
            foreach ($tasks as $task) {
                $task->user = $task->get_user()->get_name();
                $task->category = $task->get_category()->get_name();
                $task->tags = $task->get_tags(true);
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
            $task->user = $task->get_user()->get_name();
            $task->category = $task->get_category()->get_name();
            $task->tags = $task->get_tags(true);
            $task->comments = $task->get_comments(true);
        }

        return $this->response->renderJson($task->toArray());
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
}
