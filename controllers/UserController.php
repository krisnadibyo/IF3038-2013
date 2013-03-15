<?php
class UserController extends Controller
{
    protected function init()
    {
        App::loadModel('User');
        App::loadModel('Task');

        Session::init();
        if (!Session::loggedIn() && !$this->request->getParam('magicauth')) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }
    }
}
