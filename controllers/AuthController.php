<?php
class AuthController extends Controller
{
    protected function init()
    {
        App::loadModel('User');
        Session::init();
    }

    // POST /auth/login + JSON Data
    public function login()
    {
        $data = null;
        if (!$this->_isPOSTandHasData($data) ||
            !isset($data['username']) || !isset($data['password']) ||
            Session::loggedIn()) {
            return $this->response->nullJson();
        }

        if (!$user = User::getOneByUsername($data['username'])) {
            return $this->response->nullJson();
        }

        if (md5($data['password']) === $user->get_password()) {
            Session::login($user->get_username());
            return $this->response->renderJson(array('status' => 'success'));
        }

        return $this->response->nullJson();
    }

    // GET /auth/logout
    public function logout()
    {
        if (Session::loggedIn()) {
            Session::logout();
            return $this->response->renderJson(array('status' => 'success'));
        }

        return $this->response->nullJson();
    }
}
