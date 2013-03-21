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
    $db->executeSql('DELETE FROM tbl_' . $table . '');
}

// Users
$users = array(
    'valjean' => new User(array(
        'name' => 'Jean Valjean',
        'username' => 'valjean',
        'password' => md5('valjean123'),
        'email' => 'jeanvaljean@lemiz.com',
        'birthday' => '1980-10-10',
        'avatar' => 'valjean.jpg',
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
        'avatar' => 'eponine.jpg',
        'bio' => 'Daugther of Thernadier'
    )),
);
foreach ($users as $u) { $u->save_new(); }

// Categories
// valjean's categories
$v_uncategorized = new Category(array(
    'name' => 'Uncategorized',
    'user_id' => $users['valjean']->get_id(),
));
$v_uncategorized->save_new();

$v_musical = new Category(array(
    'name' => 'Musical',
    'user_id' => $users['valjean']->get_id(),
));
$v_musical->save_new();

$v_misc = new Category(array(
    'name' => 'Misc',
    'user_id' => $users['valjean']->get_id(),
));
$v_misc->save_new();

// javert's categories
$j_uncategorized = new Category(array(
    'name' => 'Uncategorized',
    'user_id' => $users['javert']->get_id(),
));
$j_uncategorized->save_new();

$j_military = new Category(array(
    'name' => 'Military',
    'user_id' => $users['javert']->get_id(),
));
$j_military->save_new();

// eponine's categories
$e_uncategorized = new Category(array(
    'name' => 'Uncategorized',
    'user_id' => $users['eponine']->get_id(),
));
$e_uncategorized->save_new();

$e_musical = new Category(array(
    'name' => 'Musical',
    'user_id' => $users['eponine']->get_id(),
));
$e_musical->save_new();

// Tasks
$tasks = array(
    // eponine's tasks
    'singBringHimHome' => new Task(array(
        'name' => 'Sing Bring Him Home',
        'attachment' => 'none',
        'deadline' => '2013-05-05',
        'user_id' => $users['eponine']->get_id(),
        'assignee_id' => (int) $users['valjean']->get_id(),
        'category_id' => (int) $e_uncategorized->get_id(),
    )),
    'protectMarius' => new Task(array(
        'name' => 'Protect Marius',
        'attachment' => 'none',
        'deadline' => '2013-05-06',
        'user_id' => $users['eponine']->get_id(),
        'assignee_id' => null,
        'category_id' => (int) $e_uncategorized->get_id(),
    )),

    // javert's tasks
    'captureValjean' => new Task(array(
        'name' => 'Capture Valjean',
        'attachment' => 'none',
        'deadline' => '2013-12-20',
        'user_id' => (int) $users['javert']->get_id(),
        'assignee_id' => null,
        'category_id' => (int) $j_military->get_id(),
    )),

    // valjean's tasks
    'protectCosette' => new Task(array(
        'name' => 'Protect Cosette',
        'attachment' => 'none',
        'deadline' => '2013-11-01',
        'user_id' => (int) $users['valjean']->get_id(),
        'assignee_id' => null,
        'category_id' => (int) $v_uncategorized->get_id(),
    )),
    'findMarius' => new Task(array(
        'name' => 'Find Marius',
        'attachment' => 'none',
        'deadline' => '2013-12-20',
        'user_id' => (int) $users['valjean']->get_id(),
        'assignee_id' => (int) $users['eponine']->get_id(),
        'category_id' => (int) $v_misc->get_id(),
    )),
    'becomeMayor' => new Task(array(
        'name' => 'Become Mayor',
        'attachment' => 'none',
        'deadline' => '2013-02-02',
        'user_id' => (int) $users['valjean']->get_id(),
        'assignee_id' => null,
        'category_id' => (int) $v_misc->get_id(),
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
        'task_id' => $tasks['singBringHimHome']->get_id(),
        'tag_id' => $tag->get_id(),
    ));
    $tt->save_new();
    $tu = new Task_Tag(array(
        'task_id' => $tasks['findMarius']->get_id(),
        'tag_id' => $tag->get_id(),
    ));
    $tu->save_new();
}

$tasks['singBringHimHome']->set_name('Sing One Day More');
$tasks['singBringHimHome']->save();

$tasks['protectCosette']->set_category($e_uncategorized->get_id());
$tasks['protectCosette']->save();

header('Content-Type: text/plain');
echo 'Reseed complete!';
