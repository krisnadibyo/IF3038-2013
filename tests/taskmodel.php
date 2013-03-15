<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.App');
App::init();

foreach (array('User', 'Category', 'Task', 'Tag', 'Comment') as $model) {
    App::loadModel($model);
}

header('Content-Type: text/plain');

echo "Users:\n\n";
$users = User::getAll();
foreach ($users as $user) {
    $str = sprintf("    %-10s: %s", $user->get_username(), $user->get_name());
    println($str);
}

echo "\nSearch for one task named 'Day More': \n\n";

$task = Task::getOne(array('where' => array(
    array('name', 'LIKE', '%sing%'),
)));

println('   Task Name  : ' . $task->get_name() . ' (' . $task->get_user()->get_username() . ')');
println('   Owner      : ' . $task->get_user()->get_name() . ' (' . $task->get_user()->get_username() . ')');
println('   Assignee   : ' . $task->get_assignee()->get_name() . ' (' . $task->get_assignee()->get_username() . ')');
println('   Category   : ' . $task->get_category()->get_name());
println('   Tags       : ' . vh_printTags($task->get_tags()));

println("\nBy category 'Uncategorized'");
print_rmos(Task::getByCategoryName('Uncategorized'));

println("\nBy category 'Misc'");
print_rmos(Task::getByCategoryName('Misc'));
