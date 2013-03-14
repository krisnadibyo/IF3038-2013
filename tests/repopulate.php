<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.App');
App::init();
App::loadModel('Hello');

$all = Hello::getAll();
foreach ($all as $h) {
    $h->delete();
}

$h = new Hello(array('id' => 1, 'msg' => 'Les Miserables'));
$h->save_new();

$h = new Hello(array('id' => 2, 'msg' => 'The Phantom of The Opera'));
$h->save_new();
