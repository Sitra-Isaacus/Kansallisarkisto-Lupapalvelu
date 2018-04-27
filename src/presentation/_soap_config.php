<?php 
/*
 * FMAS Käyttölupapalvelu
 * Front-end: SOAP configuration file
 *
 * Created: 8.10.2015
 */

// URL of the web service

ini_set('soap.wsdl_cache_enabled', 0); 
ini_set('default_socket_timeout', 600);
//ini_set('soap.wsdl_cache_ttl', '1'); 

define("LOGIC_SERVER", "35.198.126.208/logic/");

if (HTTPS_PAALLA) {
	define("PROTOCOL_USED", "https");
} else {
	define("PROTOCOL_USED", "http");
}

define("FMAS_SERVICE_URL", PROTOCOL_USED . "://" . LOGIC_SERVER . "fmas_business_logic.php");

// URL of the LDAP service
//define("LDAP_CONNECTION_URL", "ldaps://dione.narc.fi");

?>