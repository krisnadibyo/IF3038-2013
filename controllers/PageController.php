<?php
class PageController extends Controller
{
    public function init()
    {
        Session::init();
    }

    public function approot_js()
    {
        $data = array(
            'root' => Config::$config['root_path'],
            'script_name' => Config::$config['script_name']
        );
        return $this->response->renderView('pages.approot_js', $data, 'text/javascript');
    }

    public function index()
    {
        if (Session::loggedIn()) {
            return $this->response->redirect(vh_link('page/dashboard'));
        }

        $data = array(
            'title' => 'Home - MadToDo',
            'isHome' => true,
            'headerScripts' => array(
                'js/madtodo.js',
                'js/xhr.js',
                'js/user.js',
            ),
            'footerScripts' => array(
                'js/home.js',
            )
        );

        return $this->response->renderView('pages.home', $data);
    }

    public function home()
    {
        return $this->response->redirect(vh_link(''));
    }

    public function dashboard()
    {
        if (!Session::loggedIn()) {
            return $this->response->redirect(vh_link(''));
        }

        App::loadModel('User');
        $user = User::getOneByUsername(Session::get('username'));

        $data = array(
            'title' => 'Dashboard - MadToDo',
            'isDashboard' => true,
            'headerScripts' => array(
                'js/madtodo.js',
                'js/xhr.js',
                'js/user.js',
                'js/task.js',
            ),
            'footerScripts' => array(
                'js/dashboard.js',
                'js/dialog.js',
            ),
            'user' => $user->toArray(),
        );

        return $this->response->renderView('pages.dashboard', $data);
    }

    public function profile()
    {
        if (!Session::loggedIn()) {
            return $this->response->redirect(vh_link(''));
        }

        App::loadModel('User');
        $user = User::getOneByUsername(Session::get('username'));

        $data = array(
            'title' => 'Profile - MadToDo',
            'isProfile' => true,
            'headerScripts' => array(
                'js/madtodo.js',
                'js/xhr.js',
                'js/user.js',
                'js/task.js',
            ),
            'footerScripts' => array(
                'js/profile.js',
                'js/dialog.js',
            ),
            'user' => $user->toArray(),
        );

        return $this->response->renderView('pages.profile', $data);
    }

    public function search($filter="", $keyword="")
    {
        if (!Session::loggedIn()) {
            return $this->response->redirect(vh_link(''));
        }

        App::loadModel('User');
        App::loadModel('Category');
        App::loadModel('Task');
        App::loadModel('Tag');
        App::loadModel('Comment');

        $user = User::getOneByUsername(Session::get('username'));

        $data = array(
            'title' => 'Profile - MadToDo',
            'isSearch' => true,
            'headerScripts' => array(
                'js/madtodo.js',
                'js/xhr.js',
                'js/user.js',
                'js/task.js',
                'js/search.js',
            ),
            'footerScripts' => array(
                'js/search.js',
            ),
            'user' => $user->toArray(),
            'filter' => $filter,
            'keyword' => $keyword,
        );

        if ($filter == 'all') {
            $users = User::getAll(array(
                'where' => array(
                    array('username', 'LIKE', '%' . $keyword . '%'),
                ),
            ), false);
            $data['users'] = $users;

            $categories = Category::getAll(array(
                'where' => array(
                    array('name', 'LIKE', '%' . $keyword . '%'),
                    'AND',
                    array('user_id', '=', $user->get_id()),
                ),
            ), false);
            $data['categories'] =  $categories;

            $tasks = Task::getAll(array(
                'where' => array(
                    array('name', 'LIKE', '%' . $keyword . '%'),
                ),
            ));

            $data['tasks'] = $tasks;
        }
        else if ($filter == 'username') {
            $users = User::getAll(array(
                'where' => array(
                    array('username', 'LIKE', '%' . $keyword . '%'),
                ),
            ), false);
            $data['users'] = $users;
        }
        else if ($filter == 'category') {
            $categories = Category::getAll(array(
                'where' => array(
                    array('name', 'LIKE', '%' . $keyword . '%'),
                    'AND',
                    array('user_id', '=', $user->get_id()),
                ),
            ), false);
            $data['categories'] =  $categories;
        }
        else if ($filter == 'task') {
            $tasks = Task::getAll(array(
                'where' => array(
                    array('name', 'LIKE', '%' . $keyword . '%'),
                ),
            ));

            $data['tasks'] = $tasks;
        }
        else {
            return $this->response->nullJson();
        }

        return $this->response->renderView('pages.search', $data);
    }

    // Private functions
    public function _complete(&$task) {
            $task->user = $task->get_user()->get_name();
            $assignee = $task->get_assignee();
            if ($assignee != null) {
                $task->assignee = $assignee->get_name();
            }
            $task->category = $task->get_category()->get_name();
            $task->tags = $task->get_tags(true);
            $task->comments = $task->get_comments(true);
    }

    /* temporary: */
    public function install()
    {
        return $this->response->redirect(vh_slink('install.html'));
    }

    public function test()
    {
        return $this->response->redirect(vh_link('hello/view/'
            . urlencode('The quick<br/>brown fox<br/>jumps over<br/>the lazy<br/>dog')));
    }

    public function logout()
    {
        Session::logout();
        return $this->response->redirect(vh_link(''));
    }
}
