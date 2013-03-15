<?php
class TagController extends Controller
{
    protected function init()
    {
        App::loadModel('Tag');
        App::loadModel('Task');

        Session::init();
        if (!Session::loggedIn() && !$this->request->getParam('magicauth')) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }
    }

    // GET /tag/all
    public function all()
    {
        $tags = Tag::getAll();
        $this->response->renderJson($tags, true);
    }

    // GET /tag/hint/<name>
    public function hint($name='')
    {
        $hints = array();

        $tags = Tag::getAll(array(
            'where' => array(
                array('name', 'LIKE', $name . '%'),
            ),
        ));
        if ($name != '' && $tags != null) {
            foreach ($tags as $tag) {
                $hints[] = $tag->get_name();
            }
        }

        $this->response->renderJson($hints);
    }

    // GET /tag/name/<name>
    public function name($name='')
    {
        $tag = Tag::getOneByName($name);
        $tag = !$tag ? null : $tag->toArray();

        $this->response->renderJson($tag);
    }

    // GET /tag/get/<id>
    public function get($id=0)
    {
        $tag = Tag::getOneById($id);
        $tag = !$tag ? null : $tag->toArray();

        $this->response->renderJson($tag);
    }

    // POST /tag/create -- data=JSON
    public function create()
    {
        if ($this->request->isGET() || !$this->request->getParam('data')) {
            return $this->response->nullJson();
        }

        $tagData = json_decode($this->request->getParam('data'), true);
        if (!$tagData || !is_array($tagData)) {
            return $this->response->nullJson();
        }

        $tag = new Tag($tagData);
        if (!$tag->validate(true)) {
            $this->response->renderJson($tag->validate());
            return;
        }

        $tag->save_new(false);
        $this->response->renderJson(array('status' => 'success'));
    }

    // POST /tag/edit/<id> -- data=JSON
    public function edit($id=0)
    {
        if ($this->request->isGET() || !$this->request->getParam('data')) {
            return $this->response->nullJson();
        }

        $tagData = json_decode($this->request->getParam('data'), true);
        if (!$tagData || !is_array($tagData)) {
            return $this->response->nullJson();
        }

        $tag = Tag::getOneById($id);
        if (!$tag) {
           return $this->response->nullJson();
        }

        foreach ($tagData as $col => $val) {
            $tag->{$col} = $val;
        }

        if (!$tag->validate(true)) {
            $this->response->renderJson($tag->validate());
            return;
        }

        $tag->save(false);
        $this->response->renderJson(array('status' => 'success'));
    }

    // POST /tag/delete/<id>
    public function delete($id=0)
    {
        if ($this->request->isGET()) {
            return $this->response->nullJson();
        }

        $tag = Tag::getOneById($id);
        if (!$tag) {
            return $this->response->nullJson();
        }

        $tag->delete();
        $this->response->renderJson(array('status' => 'success'));
    }

    // POST /tag/assign/<tag_id>/<task_id>
    public function assign($tag_id=0, $task_id=0)
    {
        if ($this->request->isGET()) {
            return $this->response->nullJson();
        }

        if (Task::getOneById($task_id) == null || Tag::getOneById($tag_id) == null) {
            return $this->response->nullJson();
        }

        $tt = new Task_Tag(array(
            'task_id' => $task_id,
            'tag_id' => $tag_id,
        ));
        $tt->save_new(false);

        $this->response->renderJson(array('status' => 'success'));
    }

    // POST /tag/unassign/<tag_id>/<task_id>
    public function unassign($tag_id, $task_id)
    {
        if ($this->request->isGET()) {
            return $this->response->nullJson();
        }

        Task_Tag::deleteWhere('task_id = :task_id AND tag_id = :tag_id', array(
            'task_id' => $task_id,
            'tag_id' => $tag_id,
        ));

        $this->response->renderJson(array('status' => 'success'));
    }
}
