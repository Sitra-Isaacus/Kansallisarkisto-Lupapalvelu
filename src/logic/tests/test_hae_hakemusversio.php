<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_hae_hakemusversio.php
 *
 * see tests/README.md for details
 */

define('FMAS', true);

include("_test_config.php");
include("func_lib.php");
include($dir."fmas_business_logic.php");

$logic = new fmas_business_logic();

handle_arguments($argv);
if (!isset($token)) include("auth.inc.php");
if (!isset($hakemusversio_id)) $hakemusversio_id = 1426; 
if (!isset($tutkimus_id)) $tutkimus_id = 1265;

$syoteparametrit = array(
    "tutkimus_id" => $tutkimus_id,
    "hakemusversio_id" => $hakemusversio_id,
    "sivu" => "hakemus_perustiedot",
    "kayttajan_rooli" => "rooli_hakija", // important
    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try {
	
	$respObj = $logic->hae_hakemusversio($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	//print_r($respObj);
	//debug_log($respObj);
	//echo "hae_hakemusversio response object is dumped to /tmp/debug_test.log (~11mb)\n";

	$cnt++;
	echo $cnt . " tests done\n";
	
} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	//print_r($ex->faultcode);
	
}

$cnt++;
echo $cnt . " tests done\n";

