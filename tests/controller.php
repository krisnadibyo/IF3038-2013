<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.App');
App::init();
App::loadController('hello');

$c = new HelloController();
$c->hello();