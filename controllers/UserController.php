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

    // GET /user/get[?username=<username>]
    public function get()
    {
    	$username = $this->request->getParam('username');
		if($username == null) {
			$username = $this->username;
		}
		
        $user = User::getOneByUsername($username);
        return $this->response->renderJson($user);
    }

    // GET /user/hint/<username>
    public function hint($username='')
    {
        $hints = array();
        $users = User::getAll(array(
            'where' => array(
                array('username', 'LIKE', $username . '%'),
            ),
        ));

        if ($users !== null && $username != '') {
            foreach ($users as $user) {
                $hints[] = $user->get_username();
            }
        }

        return $this->response->renderJson($hints);
    }

    // GET /user/getid/<username>
    public function getid($username='')
    {
        if ($username == '' || !$user = User::getOneByUsername($username)) {
            return $this->response->nullJson();
        }

        return $this->response->renderJson($user->get_id());
    }

    // POST /user/edit[?username=<username>]
    public function edit()
    {
    	$username = $this->request->getParam('username');
		if($username == null) {
			$username = $this->username;
		}
		
        $data = null;
        if (!$this->_isPOSTandHasData($data)) {
            return $this->response->nullJson();
        }

        $user = User::getOneByUsername($username);
        foreach ($data as $col => $val) {
            // opt remain on avatar & password
            if ($col == 'avatar' && $val == 'none') {
                continue;
            }

            if ($col == 'password' && $val == '') {
                continue;
            } else if ($col == 'password' && $val != '') {
                $val = md5($val);
            }

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

        App::loadModel('Category');
        $uncat = new Category(array('name' => 'Uncategorized', 'user_id' => $user->get_id()));
        $uncat->save_new();

        $task = new Task(array(
            'name' => 'Sample Task',
            'attachment' => 'none',
            'deadline' => '2014-01-01',
            'user_id' => $user->get_id(),
            'assignee_id' => null,
            'category_id' => $uncat->get_id(),
        ));
        $task->save_new();

        return $this->response->renderJson(array('status' => 'success'));
    }

    // Magic functions
    ////
    private $magicpass = 'samantha';

    // GET /user/all
    public function all()
    {
        $users = User::getAll();
        return $this->response->renderJson($users, true);
    }

    // GET /user/username/<username>
    public function username($username=null)
    {
        if (!$username || !$user = User::getOneByUsername($username)) {
            return $this->response->nullJson();
        }

        return $this->response->renderJson($user->toArray());
    }

    // POST /user/delete/<username>
    public function delete($username=null)
    {
        if (!$this->_isPOST() || !$username ||
            !$user = User::getOneByUsername($username)) {
            return $this->response->nullJson();
        }

        $user->delete();
        return $this->response->renderJson(array('status' => 'success'));
    }
}
