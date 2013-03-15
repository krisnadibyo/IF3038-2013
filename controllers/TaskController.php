<?php
class TaskController extends Controller
{
    public function init()
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

        if ($complete) {
            foreach ($tasks as $task) {
                $task->user = $task->get_user()->get_name();
                $task->category = $task->get_category()->get_name();
                $task->tags = $task->get_tags(true);
            }
        }

        $this->response->renderJson($tasks, true);
    }

    // GET /task/search_name/<name>/[<complete>]
    public function search_name($name='', $complete=false)
    {
        $tasks = Task::getAll(array(
            'where' => array(
                array('name', 'LIKE', '%' . $name . '%'),     
            ),
        ));

        if ($complete) {
            foreach ($tasks as $task) {
                $task->user = $task->get_user()->get_name();
                $task->category = $task->get_category()->get_name();
                $task->tags = $task->get_tags(true);
            }
        }

        $this->response->renderJson($tasks, true);
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

        $this->response->renderJson($hints);
    }

    // GET /task/category/<id>/[<complete>]
    public function category($category='Uncategorized', $complete=false)
    {
        $tasks = Task::getByCategoryName($category);
        if ($complete) {
            foreach ($tasks as $task) {
                $task->user = $task->get_user()->get_name();
                $task->category = $task->get_category()->get_name();
                $task->tags = $task->get_tags(true);
            }
        }

        $this->response->renderJson($tasks, true);
    }

    // GET /task/get/<id>/[<complete>]
    public function get($id=0, $complete=false)
    {
        $task = Task::getOneById($id);
        if (!$task) {
            // task not found
            $this->response->renderJson(null);
            return;
        }

        if ($complete) {
            $task->user = $task->get_user()->get_name();
            $task->category = $task->get_category()->get_name();
            $task->tags = $task->get_tags(true);
            $task->comments = $task->get_comments(true);
        }

        $this->response->renderJson($task->toArray());
    }

    // POST /task/create -- data=JSON
    public function create()
    {
        // make sure the request method is POST and sending data
        if ($this->request->isGET() || !$this->request->getParam('data')) {
            $this->response->renderJson(null);
            return;
        }

        $taskData = json_decode($this->request->getParam('data'), true);
        if (!$taskData || !is_array($taskData)) {
            // bad task data
            $this->response->renderJson(null);
            return;
        }

        $task = new Task($taskData);
        if (!$task->validate(true)) {
            // task not valid
            $this->response->renderJson($task->validate());
            return;
        }

        $task->save_new();
        $this->response->renderJson(array('status' => 'success'));
    }

    // POST /task/edit/<id> -- data=JSON
    public function edit($id=0)
    {
        // make sure the request method is POST and sending data
        if ($this->request->isGET() || !$this->request->getParam('data')) {
            $this->response->renderJson(null);
            return;
        }

        $task = Task::getOneById($id);
        if (!$task) {
            // task not found
            $this->response->renderJson(null);
            return;
        }

        $taskData = json_decode($this->request->getParam('data'), true);
        if (!$taskData || !is_array($taskData)) {
            // bad task data
            $this->response->renderJson(null);
            return;
        }

        foreach ($taskData as $col => $val) {
            $task->set($col, $val);
        }

        if (!$task->validate(true)) {
            // task not valid
            $this->response->renderJson($task->validate());
            return;
        }

        $task->save();
        $this->response->renderJson(array('status' => 'success'));
    }

    // POST /task/delete/<id>
    public function delete($id=0)
    {
        // make sure the request method is POST
        if ($this->request->isGET()) {
            $this->response->renderJson(null);
            return;
        }

        $task = Task::getOneById($id);
        if (!$task) {
            // task not found
            $this->response->renderJson(null);
            return;
        }

        $task->delete();
        $this->response->renderJson(array('status' => 'success'));
    }
}
