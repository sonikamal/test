<?php

// api.php
ini_set("soap.wsdl_cache_enabled", 0);
require_once __DIR__ . '/vendor/autoload.php';
class wenoError
{
    public $response = "Sucess";

    public static function authenticate($header_params)
    {
        if($header_params->username == 'WEX' && $header_params->password == 'WEX1') return true;
        else throw new SOAPFault('Wrong user/pass combination', 601);
    }

    /**
     * @param string $payload
     * @return string $delivery
     */
    public function receivePayload($payload)
    {

        $xml = base64_decode($payload);

        $fileName = 'message-'.rand().'.xml'; 
        $file = file_put_contents('messages/'.$fileName, $xml);
        $xml2json = simplexml_load_string($xml);
        $jsonOut = json_encode($xml2json); 
        $arrayJson = json_decode($jsonOut, TRUE);
   
        $response = "Success ". $payload ;
        return $response;

    }

}
    $serverUrl = "http://localhost/test/api.php";
    $options = [
        'uri' => $serverUrl,
    ];
    $server = new Zend\Soap\Server('wsdl', $options);

    if (isset($_GET['wsdl'])) {
    $soapAutoDiscover = new \Zend\Soap\AutoDiscover(new \Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeSequence());
    $soapAutoDiscover->setBindingStyle(array('style' => 'document'));
    $soapAutoDiscover->setOperationBodyStyle(array('use' => 'literal'));
    $soapAutoDiscover->setClass('wenoError');
    $soapAutoDiscover->setUri($serverUrl);

    header("Content-Type: text/xml");
    echo $soapAutoDiscover->generate()->toXml();
    } else {
    $soap = new \Zend\Soap\Server($serverUrl . '?wsdl');
    $soap->setObject(new \Zend\Soap\Server\DocumentLiteralWrapper(new wenoError()));
    $soap->handle();
    }