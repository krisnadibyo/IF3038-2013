<?php

$client = new SoapClient(null, array(
	'location' => 'http://localhost:8870/soap/server.php',
	'uri' => 'http://localhost:8870/soap/'
));

function sample_createCategory() {
	global $client;
	$data = array(
		'name' => 'Category From SOAP',
		'user_id' => 1
	);
	
	return $client->createCategory(json_encode($data));
}

function sample_createTask() {
	global $client;
    $data = array(
        'name' => 'Sample Task From SOAP',
        'deadline' => '2014-02-02',
        'user_id' => 1,
        'category_id' => 2,
    );

    return $client->createTask(json_encode($data));
}

function sample_createUser() {
	global $client;
    $data = array(
        'name' => 'Jim Soap',
        'username' => 'jimsoap',
        'password' => 'jimsoap123',
        'email' => 'jim@soap.soap',
        'birthday' => '2013-04-29',
        'bio' => 'I am from planet SOAP',
    );

    return $client->createUser(json_encode($data));
}

header('Content-Type: text/plain');

if(!isset($_REQUEST['sample'])) {
	echo "?sample=[category|task|user]";
}
else if ($_REQUEST['sample'] == 'category') {
    echo sample_createCategory();
}
else if ($_REQUEST['sample'] == 'task') {
    echo sample_createTask();
}
else if ($_REQUEST['sample'] == 'user') {
    echo sample_createUser();
}
else {
    echo "?sample=[category|task|user]";
}

?>