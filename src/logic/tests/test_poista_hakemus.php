<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_poista_hakemus.php
 *
 * see tests/README.md for details
 */

define('FMAS', true);

include("_test_config.php");
include("func_lib.php");
include($dir."fmas_business_logic.php");

$logic = new fmas_business_logic();

$params = handle_arguments($argv);
if (!isset($token)) include("auth.inc.php");
if (!isset($hakemusversio_id)) $hakemusversio_id = TEST_HAKEMUSVERSIO_ID; 

$syoteparametrit = array(
    "hakemusversio_id" => $hakemusversio_id,

    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try {
	
	$respObj = $logic->poista_hakemus($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);
	print_r($respObj);

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	//print_r($ex->faultcode);
	
}

if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";



