<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.App');
App::init();
App::loadModel('Hello');

$db = Db::getInstance()->executeSql('DELETE FROM hello');

$hellos = array(
    new Hello(array('id' => 1, 'msg' => 'Les Miserables')),
    new Hello(array('id' => 2, 'msg' => 'The Phantom of The Opera')),
    new Hello(array('id' => 3, 'msg' => 'Oliver!')),
);
foreach ($hellos as $h) { $h->save_new(); }

header('Content-Type: text/plain');
echo 'Reseed complete!';
