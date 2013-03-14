<?php
class Config
{
    public static $config = array(
        'root_path' => '/', // must end with '/' (e.g. '/~user/todo/')
        'script_name' => 'index.php', // set it blank if you are using url rewrite

        'default_controller' => 'page',
        'default_action' => 'index',
    );
}
