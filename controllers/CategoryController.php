<?php
class CategoryController extends Controller
{
    protected function init()
    {
        App::loadModel('Category');

        Session::init();
        if (!Session::loggedIn()) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }

        $this->username = Session::get('username');
        $this->userId   = Session::get('userid');
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

    // GET /category/name/<name>[?userId=<userId>]
    public function name($name='')
    {
        $userId = $this->request->getParam("userId");
		if($userId != null) {
			$userId = $this->userId;
		}
		
		$cat = Category::getOneByName($name, $userId);
        $cat = !$cat ? null : $cat->toArray();
        return $this->response->renderJson($cat);
    }

    // GET /category/user/[<username>][?username=<username>]
    public function user($username='')
    {
        if ($username === '') {
            $username = $this->request->getParam("username");
			if($this->username != null) {
				$username = $this->username;
			}
        } else {
            $username = $username;
        }

        $cats = Category::getAll(array(
            'select' => array('category.*'),
            'from' => 'tbl_category AS category' .
                ' LEFT JOIN tbl_user AS user ON (category.user_id = user.id)',
            'where' => array(
                array('user.username', '=', $username),
            ),
            'orderBy' => 'name',
        ));

        return $this->response->renderJson($cats, true);
    }

    // GET /category/hint/<name>
    public function hint($name='')
    {
        $name = urldecode($name);

        $hints = array();
        $cats = Category::getAll(array(
            'where' => array(
                array('name', 'LIKE', $name . '%')
            ),
        ));

        if ($cats != null && $name != '') {
            foreach ($cats as $cat) {
                $hints[] = $cat->get_name();
            }
        }

        return $this->response->renderJson($hints);
    }

    // POST /category/create/<name>[?userId=<userId>]
    public function create($name='')
    {
    	$userId = $this->request->getParam("userId");
		if($userId != null) {
			$userId = $this->userId;
		}
		
        if (!$this->_isPOST() || $name == '') {
            return $this->response->nullJson();
        }

        $name = urldecode($name);
        $cat = new Category(array('name' => $name, 'user_id' => $userId));
        if (($validation = $cat->validate()) !== array()) {
            return $this->response->renderJson($validation);
        }

        $cat->save_new(false);
        return $this->response->renderJson(array('status' => 'success'));
    }
	
	// POST /category/rename/<name>/<newname>[?userId=<userId>]
	public function rename($name='', $newname='')
	{
		$userId = $this->request->getParam("userId");
		if($userId != null) {
			$userId = $this->userId;
		}
		
		if (!$this->_isPOST() || $name == '' || $newname == '') {
            return $this->response->nullJson();
        }
		
		$name = urldecode($name);
		$newname = urldecode($newname);
		
		if(!$cat = Category::getOneByName($name, $userId)) {
			return $this->response->nullJson();
		}
		
		$cat->set_name($newname);
		$cat->save(false);
		return $this->response->renderJson(array('status' => 'success'));
	}

    // POST /category/delete/<name>
    public function delete($name='')
    {
    	$userId = $this->request->getParam("userId");
		if($userId != null) {
			$userId = $this->userId;
		}
		
        $name = urldecode($name);
        App::loadModel('Task');
        if (!$this->_isPOST() || !$cat = Category::getOneByName($name, $userId)) {
            return $this->response->nullJson();
        }

		/*
        if (($tasks = Task::getByCategoryName($name, $this->userId)) !== null) {
            foreach ($tasks as $task) {
                $task->delete();
            }
        }
		*/

        $cat->delete();
        return $this->response->renderJson(array('status' => 'success'));
    }
}
