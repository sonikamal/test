<?php
ini_set("soap.wsdl_cache_enabled", 0);
require_once __DIR__ . '/vendor/autoload.php';
$client = new Zend\Soap\Client('http://localhost/test/api.php?wsdl');
try {
    $message = ' -> Hello World';
    $delivery = $client->call('receivePayload',[['payload' => $message]]);
    var_dump($delivery);
} catch (Exception $ex) {
    $logs = array (
        'SOAP Last Request Headers:'    => $client->getLastRequestHeaders(),
        'SOAP Last Request:'			=> $client->getLastRequest(),
        'SOAP Response Headers:'		=> $client->getLastResponseHeaders(),
        'SOAP Response:'				=> $client->getLastResponse(),
    );
    var_dump($ex->getMessage());
    var_dump($logs);
}
