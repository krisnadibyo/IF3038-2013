<?php

ini_set('soap.wsdl_cache_enabled', '0');

require_once dirname(__FILE__).'../core/Core.php';
import('core.App');
App::init();

$server = new SoapServer(null, array('uri' => 'http://localhost:8870/soap/'));

$server->addFunction('createCategory');
$server->addFunction('createTask');
$server->addFunction('createUser');

function createCategory($json) {
	App::loadModel('Category');
	
	$data = json_decode($json, true);
	
	$cat = new Category(array('name' => $data['name'], 'user_id' => $data['user_id']));
	
	$validation = $cat->validate();
	if($validation !== array()) {
		return json_encode($validation);
	}
	
	$cat->save_new(false);
	return json_decode(array("status" => "success"));
}

function createTask($json) {
	App::loadModel('Task');
	App::loadModel('User');
	App::loadModel('Category');
	
	$data = json_decode($json, true);
	
	$task = new Task($data);
	
	$validation = $task->validate();
	if($validation !== array()) {
		return json_encode($validation);
	}
	
	$task->save_new(false);
	return json_decode(array("status" => "success", "id" => $task->get_id()));
}

function createUser($json) {
	App::loadModel('User');
	
	$data = json_decode($json, true);
	$data['password'] = md5($data['password']);
	
	$user = new User($data);
	
	$validation = $user->validate();
	if($validation !== array()) {
		return json_encode($validation);
	}
	$user->save_new(false);
	
	App::loadModel('Category');
	$uncat = new Category(array('name' => 'Uncategorized', 'user_id' => $user->get_id()));
	$uncat->save_new(false);
	
	App::loadModel('Task');
	$task = new Task(array(
        'name' => 'Sample Task',
        'attachment' => 'none',
        'deadline' => '2014-01-01',
        'user_id' => $user->get_id(),
        'assignee_id' => null,
        'category_id' => $uncat->get_id(),
    ));
    $task->save_new();
	
	return json_encode(array("status" => "success"));
}

?>