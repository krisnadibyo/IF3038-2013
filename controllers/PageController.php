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
            ),
        );

        return $this->response->renderView('pages.dashboard', $data);
    }

    public function profile()
    {
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
            ),
        );

        return $this->response->renderView('pages.profile', $data);
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

    public function magiclogin()
    {
        App::loadModel('User');

        $user = User::getAll();
        $user = $user[0];
        Session::login($user->get_username());

        return $this->response->write("Logged in!");
    }

    public function logout()
    {
        Session::logout();
        return $this->response->write("Logged out!");
    }
}
