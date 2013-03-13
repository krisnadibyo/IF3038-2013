<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.App');
App::init();
App::loadModel('Hello');

header('Content-Type: text/plain');
println("Using model Hello\n");

$qArgs = array(
    'select' => array('id', 'msg'),
    'where' => array(
        array('id' , '=', 0),
        'OR',
        array('msg', 'LIKE', '%Les'),
    ),
    'limit' => 10,
    'offset' => 0,
    'orderBy' => 'id',
);

println("(1) Normal createSql without argument:");
println(Hello::createSql());

println("\n(2) createSql with argument:");
println(Hello::createSql($qArgs));

println("\nexecute (1):");
print_r($all = Hello::getAll());

println("\nexecute (2):");
print_r(Hello::getAll($qArgs));

println("\nNew Hello object and save");

$h = new Hello(array('msg' => 'The Wizard of Oz'));
$h->save_new();

print_r($h);

println("Edit and save the new Hello object");

$h->set('msg', 'Spamalot');
$h->save();

println("\nExecute (1) again:");
print_r($all = Hello::getAll());

println("\nDelete the last object (row) returned");
$all[count($all) - 1]->delete();

println("\nExecute (1) again:");
print_r($all = Hello::getAll());

