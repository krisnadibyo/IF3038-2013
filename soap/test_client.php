<?php

$client = new SoapClient(null, array(
	'location' => 'http://localhost:8870/soap/test_server.php',
	'uri' => 'http://localhost:8870/soap/'
));

header('Content-Type: text/plain');

//test add
$a = 40; $b = 27;
echo "a + b = ".$client->add($a, $b)."\n";

//test json
$json = json_encode(array('foo' => 'bar'));
echo $client->testJson($json)."\n";

?>