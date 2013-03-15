<?php
class Session
{
    public static function init()
    {
        session_start();
    }

    public static function set($key, $val)
    {
        $_SESSION[$key] = $val;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function loggedIn()
    {
        if (self::get('login') == null) {
            return false;
        }

        return true;
    }

    public static function login($user)
    {
        self::set('login', true);
        self::set('username', $user);
    }

    public static function logout()
    {
        self::remove('login');
        self::remove('username');
        self::destroy();
    }

    public static function destroy()
    {
        session_destroy();
    }
}
