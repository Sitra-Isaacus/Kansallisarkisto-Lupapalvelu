<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_lausunto.php
 *
 * see tests/README.md for details
 */

define('FMAS', true);

include("_test_config.php");
include("func_lib.php");
include($dir."fmas_business_logic.php");

$logic = new fmas_business_logic();

$params = handle_arguments($argv);

$lausunto_id = (isset($params["lausunto_id"]) ? $params["lausunto_id"] : 93); 
$hakemus_id = (isset($params["hakemus_id"]) ? $params["hakemus_id"] : 402); 
$kayttajan_rooli = (isset($params["kayttajan_rooli"]) ? $params["kayttajan_rooli"] : "rooli_kasitteleva"); 

if (!isset($token)) include("auth.inc.php");

$syoteparametrit = array(
    'lausunto_id' => $lausunto_id,
    'hakemus_id' => $hakemus_id,
    'kayttajan_rooli' => $kayttajan_rooli,	
    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("Lausunto test: ");

try{

	$respObj = $logic->hae_lausunto($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);
	$res = std2ArrayRecursive($respObj);
	
} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	print_r($respObj);
	
}	

$cnt++;

echo $cnt . " tests done\n";