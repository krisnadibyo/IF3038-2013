SOAP
====

Implementasi SOAP kami menggunakan SoapClient dan SoapServer.

Alamat SOAP server yang dibuat berada pada `http://taskmanajement.aws.af.cm/soap/server.php`
Untuk implementasi SOAP ini, kami tiak menggunakan  WSDL

Kami membuat implementasi dari fungsi berikut:

+ `createTask()`
+ `createUser()`
+ `createCategory()`

Fungsi tersebut dapat dipanggil secara remote dengan SoapClient

$client = new SoapClient(null, array(
        'location' => 'http://taskmanajement.aws.af.cm/soap/server.php',
        'uri' => 'http//taskmanajement.aws.af.cm/soap/'
    ));
	
PArameter yang dipassing pada XML SOAP adalah dalam format JSON.
