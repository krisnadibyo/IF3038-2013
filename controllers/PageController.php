<?php
class PageController extends Controller
{
    public function approot_js()
    {
        $data = array(
            'root' => Config::$config['root_path'],
            'script_name' => Config::$config['script_name']
        );
        $this->response->renderView('pages.approot_js', $data, 'text/javascript');
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

        $this->response->renderView('pages.home', $data);
    }

    public function home()
    {
        $this->response->redirect(vh_link(''));
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

        $this->response->renderView('pages.dashboard', $data);
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

        $this->response->renderView('pages.profile', $data);
    }

    /* temporary: */
    public function install()
    {
        $this->response->redirect(vh_slink('install.html'));        
    }

    public function test()
    {
        $this->response->redirect(vh_link('hello/view/'
            . urlencode('The quick<br/>brown fox<br/>jumps over<br/>the lazy<br/>dog')));
    }
}
