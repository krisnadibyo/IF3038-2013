<?php
class TagController extends Controller
{
    protected function init()
    {
        App::loadModel('Tag');
        App::loadModel('Task');

        Session::init();
        if (!Session::loggedIn()) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }
    }

    // GET /tag/all
    public function all()
    {
        $tags = Tag::getAll();
        return $this->response->renderJson($tags, true);
    }

    // GET /tag/get/<id>
    public function get($id=0)
    {
        $tag = Tag::getOneById($id);
        $tag = !$tag ? null : $tag->toArray();
        return $this->response->renderJson($tag);
    }

    // GET /tag/name/<name>
    public function name($name='')
    {
        $tag = Tag::getOneByName($name);
        $tag = !$tag ? null : $tag->toArray();
        return $this->response->renderJson($tag);
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

        return $this->response->renderJson($hints);
    }

    // POST /tag/create/<name>
    public function create($name='')
    {
        if (!$this->_isPOST() || $name == '') {
            return $this->response->nullJson();
        }

        $tag = new Tag(array('name' => $name));
        if (($validation = $tag->validate()) !== array()) {
            return $this->response->renderJson($validation);
        }

        $tag->save_new(false);
        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /tag/delete/<name>
    public function delete($name='')
    {
        if (!$this->_isPOST() || !$tag = Tag::getOneByName($name)) {
            return $this->response->nullJson();
        }

        $tag->delete();
        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /tag/reassign/<task_id> -- data=JSON (array of tag names)
    public function reassign($task_id)
    {
        $tagArr = null;
        if (!$this->_isPOSTandHasData($tagArr)) {
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
        return $this->response->renderJson(array('status' => 'success'));
    }

    // Rarely used
    // POST /tag/edit/<id> + JSON data
    public function edit($id=0)
    {
        $tagData = null;
        if (!$this->_isPOSTandHasData($tagData) ||
            !$tag = Tag::getOneById($id)) {
            return $this->response->nullJson();
        }

        foreach ($tagData as $col => $val) {
            $tag->set($col, $val);
        }

        if (($validation = $tag->validate()) !== array()) {
            return $this->response->renderJson($validation);
        }

        $tag->save(false);
        return $this->response->renderJson(array('status' => 'success'));
    }
}
