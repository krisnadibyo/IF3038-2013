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
    public static function init()
    {
        Router::init();
        Db::loadConfig(DbConfig::$config);
    }

    public static function run()
    {
        self::init();

        /* TODO: Deal with controller and view */
    }

    public static function loadModel($modelName)
    {
        import('models.' . $modelName);
        eval($modelName . '::load();');
    }
}
