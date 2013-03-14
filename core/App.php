<?php
import('config.config');
import('config.db');
import('core.Router');
import('core.Db');
import('core.lib.Model');
import('core.lib.Controller');
import('core.lib.Session');
import('core.lib.ViewHelper');

class App
{
    public static function init()
    {
        Router::init();
        Db::loadConfig(DbConfig::$config);
    }

    public static function dispatch()
    {
        $controller = (string) Router::getController();
        if (!$controller) {
            $controller = Config::$config['default_controller'];
        }

        $action = (string) Router::getAction();
        if (!$action) {
            $action = Config::$config['default_action'];
        }

        $params = Router::getParams();

        try {
            $cobj = self::loadController($controller);
            if (method_exists($cobj, $action)) {
                call_user_func_array(array($cobj, $action), $params);
            } else {
                throw new Exception("Action did not exist", 1);
            }
        } catch (Exception $e) {
            $res = new Response();
            $res->setHeader('Status: 404 Not Found');
            $res->renderView('pages.404');

            exit();
        }
    }

    public static function run()
    {
        self::init();
        self::dispatch();
    }

    public static function loadModel($modelName)
    {
        import('models.' . $modelName);
        call_user_func(array($modelName, 'load'));
    }

    public static function loadController($ctrlName)
    {
        $ctrlName[0] = strtoupper($ctrlName[0]);
        $ctrlClass = $ctrlName . 'Controller';
        import('controllers.' . $ctrlClass);

        return new $ctrlClass();
    }
}
