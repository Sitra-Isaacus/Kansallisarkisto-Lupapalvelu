<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_laheta_viesti.php
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
$hakemus_id = (isset($params["hakemus_id"]) ? $params["hakemus_id"] : null);
$vastaanottaja = (isset($params["vastaanottaja"]) ? $params["vastaanottaja"] : null);
$on_vastaus = (isset($params["on_vastaus"]) ? $params["on_vastaus"] : false);
$parent_id = (isset($params["parent_id"]) ? $params["parent_id"] : null);

if (!isset($token)) include("auth.inc.php");

$syoteparametrit = array(
    "kayttajan_rooli" => $kayttajan_rooli,	
	"on_vastaus" => $on_vastaus,
    "data" => array(
        "vastaanottaja" => $vastaanottaja,
		"parent_id" => $parent_id,
		"vastaus" => generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20),
		"viesti" => generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20) . " " . generateRandomString(20)
    ),	
    "hakemus_id" => $hakemus_id,			
    'token' => $token,
    'kayt_id' => $user_id
);

$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try{
	
	$respObj = $logic->laheta_viesti($syoteparametrit);
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
