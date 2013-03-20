<?php
class UserController extends Controller
{
    private $username = null;

    protected function init()
    {
        App::loadModel('User');
        App::loadModel('Task');

        Session::init();
        if (!Session::loggedIn() && !$this->request->getParam('magicauth') && Router::getAction() !== 'register') {
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

        $this->username = Session::get('username');
    }

    // GET /user/get (logged user)
    public function get()
    {
        $user = User::getOneByUsername($this->username);
        return $this->response->renderJson($user);
    }

    // POST /user/edit (logged user edit)
    public function edit()
    {
        $data = null;
        if (!$this->_isPOSTandHasData($data)) {
            return $this->response->nullJson();
        }

        $user = User::getOneByUsername($this->username);
        foreach ($data as $col => $val) {
            $user->set($col, $val);
        }

        $validation = $user->validate(false, true);
        if ($validation !== array()) {
            return $this->response->renderJson($validation);
        }

        $user->save(false);
        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /user/register + JSON data
    public function register()
    {
        $data = null;
        if (!$this->_isPOSTandHasData($data)) {
            return $this->response->nullJson();
        }

        $data['password'] = md5($data['password']);
        $user = new User($data);

        $validation = $user->validate();
        if ($validation !== array()) {
            return $this->response->renderJson($validation);
        }

        $user->save_new(false);
        return $this->response->renderJson(array('status' => 'success'));
    }

    // Magic functions
    ////
    private $magicpass = 'samantha';

    // GET /user/all/<magic_password>
    public function all($magicpass=null)
    {
        if ($magicpass != $this->magicpass) {
            return $this->response->nullJson();
        }

        $users = User::getAll();
        return $this->response->renderJson($users, true);
    }

    // GET /user/username/<username>/<magic_password>
    public function username($username=null, $magicpass=null)
    {
        if (!$username || $magicpass != $this->magicpass ||
            !$user = User::getOneByUsername($username)) {
            return $this->response->nullJson();
        }

        return $this->response->renderJson($user->toArray());
    }

    // POST /user/delete/<username>/<magic_password>
    public function delete($username=null, $magicpass=null)
    {
        if (!$this->_isPOST() || !$username ||
            $magicpass != $this->magicpass ||
            !$user = User::getOneByUsername($username)) {
            return $this->response->nullJson();
        }

        $user->delete();
        return $this->response->renderJson(array('status' => 'success'));
    }
}
