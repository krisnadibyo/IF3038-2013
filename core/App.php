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
        $controller = Router::getController();
        if (!$controller) {
            $controller = Config::$config['default_controller'];
        }

        $action = (string) Router::getAction();
        if (!$action) {
            $action = Config::$config['default_action'];
        }

        $paramStr = '';
        $params = Router::getParams();
        if (count($params) > 0) {        
            for ($i = 0; $i < count($params); $i++) {
                $paramStr .= "'" . $params[$i] . "'";
                if ($i != count($params) - 1) {
                    $paramStr .= ', ';
                }
            }
        }

        $cobj = self::loadController($controller);
        eval('$cobj->' . $action . '(' . $paramStr . ');');
    }

    public static function run()
    {
        self::init();
        self::dispatch();
    }

    public static function loadModel($modelName)
    {
        import('models.' . $modelName);
        eval($modelName . '::load();');
    }

    public static function loadController($ctrlName)
    {
        $ctrlName[0] = strtoupper($ctrlName[0]);
        $ctrlClass = $ctrlName . 'Controller';
        import('controllers.' . $ctrlClass);

        return new $ctrlClass();
    }
}
