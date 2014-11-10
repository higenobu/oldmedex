<?php
mb_internal_encoding('utf-8');
mb_http_output('utf-8');

require_once 'SOAP/Server.php';
$server = new SOAP_Server;
$server->_auto_translation = true;
$server->trace = 1;
require_once 'medex_server.php';

$soapclass = new SOAP_Medex_Server();
$server->addObjectMap($soapclass,'urn:SOAP_Medex_Server');

if (isset($_SERVER['REQUEST_METHOD']) &&
    $_SERVER['REQUEST_METHOD']=='POST') {
    $server->service($HTTP_RAW_POST_DATA);
} else {
    require_once 'SOAP/Disco.php';
    $disco = new SOAP_DISCO_Server($server,'MedexServer');
    header("Content-type: text/xml");
    if (isset($_SERVER['QUERY_STRING']) &&
       strcasecmp($_SERVER['QUERY_STRING'],'wsdl')==0) {
        echo $disco->getWSDL();
    } else {
        echo $disco->getDISCO();
    }
    exit;
}
?>
