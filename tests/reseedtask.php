<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.App');
App::init();

App::loadModel('User');
App::loadModel('Category');
App::loadModel('Task');
App::loadModel('Tag');
App::loadModel('Comment');

$db = Db::getInstance();
/* Clear table contents */
foreach (array('user', 'category', 'task', 'tag', 'task_tag') as $table) {
    $db->executeSql('DELETE FROM ' . $table);    
}

$users = array(
    'valjean' => new User(array(
        'name' => 'Jean Valjean',
        'username' => 'valjean',
        'password' => md5('valjean123'),
        'email' => 'jeanvaljean@lemiz.com',
        'birthday' => '1980-10-10',
        'avatar' => 'jeanvaljean.jpg',
        'bio' => 'Cossete\'s stepfather'
    )),
    'javert' => new User(array(
        'name' => 'Inspector Javert',
        'username' => 'javert',
        'password' => md5('inspector'),
        'email' => 'javert@lemiz.com',
        'birthday' => '1970-11-24',
        'avatar' => 'javert.jpg',
        'bio' => 'Inspector'
    )),
    'eponine' => new User(array(
        'name' => 'Eponine Thernadier',
        'username' => 'eponine',
        'password' => md5('onmyown'),
        'email' => 'eponine@lemiz.com',
        'birthday' => '1990-10-02',
        'avatar' => 'epoinie.jpg',
        'bio' => 'Daugther of Thernadier'
    )),
);
foreach ($users as $u) { $u->save_new(); }

// Category
$category = new Category(array(
    'name' => 'Uncategorized'
));
$category->save_new();
$catId = $category->get('id');

// Task
$tasks = array(
    'singBringHimHome' => new Task(array(
        'name' => 'Sing Bring Him Home',
        'attachment' => '',
        'deadline' => '2013-05-05',
        'user_id' => $users['eponine']->get('id'),
        'assignee_id' => $users['valjean']->get('id'),
        'category_id' => $catId,
    )),
    'captureValjean' => new Task(array(
        'name' => 'Capture Valjean',
        'attachment' => '',
        'deadline' => '2013-12-20',
        'user_id' => $users['javert']->get('id'),
        'assignee_id' => null,
        'category_id' => $catId,
    )),
    'protectCosette' => new Task(array(
        'name' => 'Protect Cosette',
        'attachment' => '',
        'deadline' => '2013-11-01',
        'user_id' => $users['valjean']->get('id'),
        'assignee_id' => null,
        'category_id' => $catId,
    )),
);
foreach ($tasks as $t) { $t->save_new(); }

// Tags
$tags = array(
    new Tag(array('name' => 'Music')),
    new Tag(array('name' => 'Fun')),
);

foreach ($tags as $tag) {
    $tag->save_new();
    $tt = new Task_Tag(array(
        'task_id' => $tasks['singBringHimHome']->get('id'),
        'tag_id' => $tag->get('id'),
    ));
    $tt->save_new();
}

$tasks['singBringHimHome']->set('name', 'Sing One Day More');
$tasks['singBringHimHome']->save();

header('Content-Type: text/plain');
echo 'Reseed complete!';
