<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_laheta_lausuntopyynto.php
 *
 * see tests/README.md for details
 */

define('FMAS', true);

include("_test_config.php");
include("func_lib.php");
include($dir."fmas_business_logic.php");

$logic = new fmas_business_logic();

$params = handle_arguments($argv);

$kayttajan_rooli = (isset($params["kayttajan_rooli"]) ? $params["kayttajan_rooli"] : "rooli_kasitteleva");
$hakemus_id = (isset($params["hakemus_id"]) ? $params["hakemus_id"] : 391);
$laus_antaja = (isset($params["laus_antaja"]) ? $params["laus_antaja"] : 122);
$laus_pvm = (isset($params["laus_pvm"]) ? $params["laus_pvm"] : date('d/m/Y', strtotime('+2 months')));

if (!isset($token)) include("auth.inc.php");

$syoteparametrit = array(
    "kayttajan_rooli" => $kayttajan_rooli,	
    "data" => array(
        "laus_antaja" => $laus_antaja,
        "laus_pvm" => $laus_pvm,		
		"lausuntopyynto" => generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20)
    ),	
    "hakemus_id" => $hakemus_id,			
    'token' => $token,
    'kayt_id' => $user_id
);

$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try{
	
	$respObj = $logic->laheta_lausuntopyynto($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	$res = std2ArrayRecursive($respObj);
	print_r($res);

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	print_r($respObj);
	
}	
	
$cnt++;

echo $cnt . " tests done\n";
