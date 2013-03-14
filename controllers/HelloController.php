<?php
class HelloController extends Controller
{
    protected function init()
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

    public function view($msg='Hello!')
    {
        $this->response->renderView('hello.content', array(
            'title' => 'Rendered From Hello::view()',
            'msg' => urldecode($msg),
        ));
    }    
}
