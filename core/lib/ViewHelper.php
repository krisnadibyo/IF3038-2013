<?php
class ViewHelper
{
    public static function link($url)
    {
        $link = Config::$config['root_path'];
        if (Config::$config['script_name'] != '') {
            $link .= Config::$config['script_name'] . '/';
        }

        $link .= $url;
        return $link;
    }

    public static function staticLink($url)
    {
        $link = Config::$config['root_path'] . 'static/' . $url;
        return $link;
    }

    public static function render($viewPath) {
         require_once dirname(__FILE__) . '/../../views/' . preg_replace('/\./', '/', $viewPath) . '.php';
    }
}

/* Shortcuts */
function vh_link($url) {
    return ViewHelper::link($url);
}

function vh_slink($url) {
    return ViewHelper::staticLink($url);
}

function vh_render($viewPath, $data=array()) {
    if (!$data) {
        $data = array();
    }

    foreach($data as $key => $val) {
        ${$key} = $val;
    }

    include dirname(__FILE__) . '/../../views/' . preg_replace('/\./', '/', $viewPath) . '.php';
}
