<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: SOAP client for FMAS
 *
 * Created: 8.10.2015
 */

include_once '_soap_config.php';

function createSoapClient() {
	// create an instance of SOAP client
	$options = array('location' => FMAS_SERVICE_URL,
			'trace' => 1,
			'cache_wsdl' => WSDL_CACHE_NONE,
			'encoding'=>'UTF-8'
			//'cache_wsdl' =>  WSDL_CACHE_BOTH
	);
	return new SoapClient(FMAS_SERVICE_URL."?wsdl", $options);
}

function createBinarySoapClient() {
	// create an instance of SOAP client
	$options = array('location' => FMAS_SERVICE_URL,
			'trace' => 1,
			'cache_wsdl' => WSDL_CACHE_NONE,
			'encoding' => 'ISO-8859-1'
	);
	return new SoapClient(FMAS_SERVICE_URL.'?wsdl', $options);
}

