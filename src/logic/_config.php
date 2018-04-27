<?php
/*
 * FMAS Käyttölupapalvelu
 * Business logic layer
 * Configuration file
 *
 * Created: 8.10.2015
 */

// supress notices cause vendor stuff produces them and error output breaks SOAP xml
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

define("PRESENTATION_SERVER", "192.168.104.250/lupapalvelu_demo_v6/presentation/");
define("LOGIC_SERVER", "192.168.104.250/lupapalvelu_demo_v6/logic/");
define("DATA_SERVER", "192.168.104.250/lupapalvelu_demo_v6/data/");

define("HTTPS_PAALLA", false);
define("TOISIOLAKI_PAALLA", true); // Laita tämä päälle jos haluat järjestelmään lupaviranomaisen valtuudet

if (HTTPS_PAALLA) {
	define("PROTOCOL_USED", "https");
} else {
	define("PROTOCOL_USED", "http");
}

define("KAYTTAJAN_VARMENNUS_URL", PROTOCOL_USED . "://" . PRESENTATION_SERVER . "kayttajan_varmennus.php");
define("EMAIL_LAHETTAJA", "noreply@lupatuki.fi");
define("KAYT_LIITT_TUTK_RYHM_URL", PROTOCOL_USED . "://" . PRESENTATION_SERVER . "kayttajan_liittaminen_hakemukseen.php");

//ini_set('soap.wsdl_cache_enabled', '1'); 
//ini_set('soap.wsdl_cache_ttl', '1');  
 
$current_url = PROTOCOL_USED . "://" . $_SERVER['SERVER_NAME'] . strtok($_SERVER["REQUEST_URI"],'?');
$wsdl_xml_namespace = PROTOCOL_USED . "://" . LOGIC_SERVER;
$datasource_url = PROTOCOL_USED . "://" . DATA_SERVER . "fmas_db_api.php";

// XML namespace of this service
define("WSDL_XML_NAMESPACE", $wsdl_xml_namespace);

// URL of the data layer
define("FMAS_DATASOURCE_URL", $datasource_url);

/*
 * General SOAP error codes
 */

 // Database is not supported
 define("ERR_NO_DATABASE", "990");
 
 /*
  * DB-specific SOAP error codes
  */
  
// Invalid request format
define("ERR_INVALID_REQUEST_FORMAT", "1902");

// Unknown field name
define("ERR_UNKNOWN_FIELD_NAME", "1903");

// Invalid parameter (e.g. unknown search term)
define("ERR_INVALID_PARAMETER", "1904");

// Invalid id
define("ERR_INVALID_ID", "1905");

// Invalid token
define("ERR_INVALID_TOKEN", "1906");

// Invalid token
define("ERR_DB", "1907");

// Auth. error
define("ERR_AUTH_FAIL", "1908"); 

?>