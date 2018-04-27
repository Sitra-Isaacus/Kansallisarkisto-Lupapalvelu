<?php
/*
 * FMAS Käyttölupapalvelu
 * Database communication layer
 * Configuration file
 *
 * Created: 9.7.2015
 */

// XML namespace of this service

define("HTTPS_PAALLA", false); 
define("TOISIOLAKI_PAALLA", true); // Toisiolaki 44 §
define("DATA_SERVER", "192.168.104.250/lupapalvelu_demo_v6/data/");

if (HTTPS_PAALLA) {
	define("PROTOCOL_USED", "https");
} else {
	define("PROTOCOL_USED", "http");
}

$current_url = PROTOCOL_USED . "://" . $_SERVER['SERVER_NAME'] . strtok($_SERVER["REQUEST_URI"],'?');

define("WSDL_XML_NAMESPACE", PROTOCOL_USED . "://" . DATA_SERVER);

//ini_set('soap.wsdl_cache_enabled', '1');
//ini_set('soap.wsdl_cache_ttl', '1');

define("MAKSIMI_LIITETIEDOSTON_KOKO", 500000000); 

// Aika kuinka pitkään hakemusversio pysyy lukittuna
define("HAKEMUSVERSIO_LOCK_TIME", "1800");

/*
 * Database configuration
 */

 // Database name
define("DB_DATABASE_NAME", "insert_your_db_name_here");

// User name
define("DB_USER_NAME", "insert_your_db_user_here");

// password
define("DB_PASSWORD", "insert_your_db_password_here");

/*
 * SOAP error codes
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