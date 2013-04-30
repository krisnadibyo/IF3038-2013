<?php

function add($a, $b) {
	return $a + $b;
}

function testJson($json) {
	$ret = json_decode($json, true);
	return '"foo" = '.$ret['foo'];
}

ini_set('soap.wsdl_cache_enabled', '0');

$server = new SoapServer(null, array(
	'uri' => 'http://localhost:8870/soap/'
));

$server->addFunction('add');
$server->addFunction('testJson');

$server->handle();

?>