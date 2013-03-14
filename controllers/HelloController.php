<?php
class HelloController extends Controller
{
    public function init()
    {
        App::loadModel('Hello');
    }

    public function index()
    {
        $hellos = Hello::getAll();
        $this->response->renderJson($hellos, true);
    }

    public function get($id=null)
    {
        $id = (int) $id;
        $hello = Hello::getOne(array(
            'where' => array(
                'id', '=', $id
            ),
        ));

        $this->response->renderJson($hello);
    }

    public function search_msg($msg=null)
    {
        $msg = urldecode($msg);
        $hellos = Hello::getAll(array(
            'where' => array(
                array('msg', 'LIKE', '%' . $msg . '%'),
            ),
        ));

        $this->response->renderJson($hellos, true);
    }
}
