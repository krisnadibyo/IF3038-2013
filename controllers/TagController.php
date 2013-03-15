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

    // GET /tag/get/<id>
    public function get($id=0)
    {
        $tag = Tag::getOneById($id);
        $tag = !$tag ? null : $tag->toArray();
        $this->response->renderJson($tag);
    }

    // GET /tag/name/<name>
    public function name($name='')
    {
        $tag = Tag::getOneByName($name);
        $tag = !$tag ? null : $tag->toArray();
        $this->response->renderJson($tag);
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

    // POST /tag/create/<name>
    public function create($name='')
    {
        if ($this->request->isGET() && $name == '') {
            return $this->response->nullJson();
        }

        $tag = new Tag(array('name' => $name));
        $validation = $tag->validate();
        if ($validation !== array()) {
            $this->response->renderJson($validation);
            return;
        }

        $tag->save_new(false);
        $this->response->renderJson(array('status' => 'success'));
    }

    // POST /tag/delete/<name>
    public function delete($name=0)
    {
        if ($this->request->isGET()) {
            return $this->response->nullJson();
        }

        $tag = Tag::getOneByName($name);
        if (!$tag) {
            return $this->response->nullJson();
        }

        $tag->delete();
        $this->response->renderJson(array('status' => 'success'));
    }

    // POST /tag/reassign/<task_id> -- data=JSON (array of tag names)
    public function reassign($task_id)
    {
        if ($this->request->isGET() || !$this->request->getParam('data')) {
            return $this->response->nullJson();
        }

        $tagArr = json_decode($this->request->getParam('data'), true);
        if (!$tagArr || !is_array($tagArr)) {
            return $this->response->nullJson();
        }

        $tagIds = array();
        foreach ($tagArr as $tagName) {
            if (!$tag = Tag::getOneByName($tagName)) {
                $tag = new Tag(array('name' => $tagName));
                $tag->save_new();
            }
            $tagIds[] = $tag->get_id();
        }

        Tag::reassign($tagIds, $task_id);
        $this->response->renderJson(array('status' => 'success'));
    }

    // Rarely used
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
}
