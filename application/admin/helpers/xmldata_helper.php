<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*

Reference
http://stackoverflow.com/questions/20393140/how-to-read-soap-response-xml-in-php
*/

function read_soap_xml_data($xml_path, $param = array()){
	$xmldata = file_get_contents($xml_path);
	$xml = new SimpleXMLElement($xmldata);
	$namespaces = $xml->getNamespaces(true);
	$xml->registerXPathNamespace("soap", $namespaces['env']);	
	$body = $xml->xpath("//soap:Body");
	return $body;
}
