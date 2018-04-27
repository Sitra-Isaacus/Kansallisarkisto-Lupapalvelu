<?php

if (!defined('FMAS')) die('Direct access not allowed');

// allow cli only, no browser
if (isset($_SERVER['REQUEST_URI'])) {
    die("Browser access not allowed.");
}

/**
 *  test user credentials - lowest access role
 */

define('TEST_USER_EMAIL', "thl2@t.t");
define('TEST_USER_PASS', "Salasana1");

define('TEST_TUTKIMUS_ID', 1231);
define('TEST_HAKEMUSVERSIO_ID', 1392);

$user_email = TEST_USER_EMAIL;
$user_pass = TEST_USER_PASS;

define('USER_EMAIL', "henri.tenhunen@narc.fi");
define('USER_PASS', "Salasana1");

define('NEW_USER_EMAIL', "unit_test@t.t");
define('NEW_USER_PASS', "Salasana1");

define('VIRANOMAINEN_EMAIL', "thl@t.t");
define('VIRANOMAINEN_PASS', "Salasana1");

define('VIRANOMAINEN2_EMAIL', "thl2@t.t");
define('VIRANOMAINEN2_PASS', "Salasana1");

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

$dir = __DIR__ . "/../";

$_SERVER['SERVER_NAME'] = "192.168.104.250";
$_SERVER['HTTP_HOST'] = "192.168.104.250"; // not really used
$_SERVER["REQUEST_URI"] = "/lupapalvelu_demo_v6/logic/fmas_business_logic.php";

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

