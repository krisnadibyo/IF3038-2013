<?php
class Router
{
    private static $req_uri;
    private static $script_name;
    private static $script_dir;
    private static $script;

    private static $route;
    private static $routeArray;
    private static $queries;

    public static function init()
    {
        self::$req_uri = $_SERVER['REQUEST_URI'];
        self::$script_name = $_SERVER['SCRIPT_NAME'];
        self::$script_dir = preg_replace('#(.*)/.*\.php#', '$1/', self::$script_name);

        self::$script = preg_match('#' . self::$script_name . '#', self::$req_uri)
            ? self::$script_name
            : self::$script_dir;

        preg_match('#^' . self::$script . '([^\?]*)($|\?.*$)#', self::$req_uri, $matches);

        self::$route = $matches[1];
        self::$route = preg_replace('#^/(.*)$#', '$1', self::$route);
        self::$route = preg_replace('#(.*)/$#', '$1', self::$route);
        self::$routeArray = explode('/', self::$route);

        self::$queries = $matches[2];
    }

    public static function getRoute()
    {
        return self::$routeArray;
    }

    public static function getController()
    {
        return count(self::$routeArray) >= 1 ? self::$routeArray[0] : null;
    }

    public static function getAction()
    {
        return count(self::$routeArray) >= 2 ? self::$routeArray[1] : null;
    }

    public static function getParams()
    {
        return array_slice(self::$routeArray, 2);
    }

    public static function getQueries()
    {
        return self::$queries;
    }
}
