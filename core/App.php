<?php
import('config.config');
import('config.db');
import('core.Router');
import('core.Db');
import('core.lib.Model');
import('core.lib.Controller');
import('core.lib.Session');

class App
{
    private static $config;

    public static function init()
    {
        self::$config = Config::$config;
        Router::init();
        Db::loadConfig(DbConfig::$config);
    }

    public static function run()
    {
        self::init();

        /* TODO: Deal with controller and view */
        $controller = Router::getController();
        if (!$controller) {
            $controller = self::$config['default_controller'];
        }

        $action = (string) Router::getAction();
        if (!$action) {
            $action = self::$config['default_action'];
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
