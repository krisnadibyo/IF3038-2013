<?php
class UserController extends Controller
{
    private $username = null;

    protected function init()
    {
        App::loadModel('User');
        App::loadModel('Task');

        Session::init();
        if (!Session::loggedIn() && !$this->request->getParam('magicauth')) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }

        // Magicauth        
        if ($this->request->getParam('magicauth')) {
            if ($u = $this->request->getParam('username')) {
                if (!User::getOneByUsername($u)) {
                    $this->response->renderJson('Not Authenticated!');
                    exit();
                } else {
                    $this->username = $u;
                    return;
                }
            }

            $this->response->renderJson('Not Authenticated!');
            exit();
        }

        $username = Session::get('user')->get_username();
    }

    // GET /user/get (logged user)
    public function get()
    {
        $user = User::getOneByUsername($this->username);
        $this->response->renderJson($user);
    }

    // POST /user/edit (logged user edit)
    public function edit()
    {
        
    }

    // POST /user/register + JSON data
    public function register()
    {
        
    }

    // POST /user/delete/<username>/<magic_password>
    public function delete()
    {
        $magicpass = 'samantha';
    }
}
