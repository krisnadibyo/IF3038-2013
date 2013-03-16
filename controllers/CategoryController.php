<?php
class CategoryController extends Controller
{
    protected function init()
    {
        App::loadModel('Category');

        Session::init();
        if (!Session::loggedIn() && !$this->request->getParam('magicauth')) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }
    }

    // GET /category/all
    public function all()
    {
        $cats = Category::getAll();
        return $this->response->renderJson($cats, true);
    }

    // GET /category/get/<id>
    public function get($id=0)
    {
        $cat = Category::getOneById($id);
        $cat = !$cat ? null : $cat->toArray();
        return $this->response->renderJson($cat);
    }

    // GET /category/name/<name>
    public function name($name='')
    {
        $cat = Category::getOneByName($name);
        $cat = !$cat ? null : $cat->toArray();
        return $this->response->renderJson($cat);
    }

    // POST /category/create/<name>
    public function create($name='')
    {
        if (!$this->_isPOST() || $name == '') {
            return $this->response->nullJson();
        }

        $cat = new Category(array('name' => $name));
        if (($validation = $cat->validate()) !== array()) {
            return $this->response->renderJson($validation);
        }

        $cat->save_new(false);
        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /category/delete/<name>
    public function delete($name='')
    {
        if (!$this->_isPOST() || !$cat = Category::getOneByName($name)) {
            return $this->response->nullJson();
        }

        $cat->delete();
        return $this->response->renderJson(array('status' => 'success'));
    }
}
