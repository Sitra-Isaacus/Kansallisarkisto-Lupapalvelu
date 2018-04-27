<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_ota_hakemus_viranomaiskasittelyyn.php
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
$kasittelija = (isset($params["kasittelija"]) ? $params["kasittelija"] : VIRANOMAINEN_ID);

if (!isset($token)) include("auth.inc.php");

$syoteparametrit = array(
    "kayttajan_rooli" => $kayttajan_rooli,	
    "hakemus_id" => $hakemus_id,	
    "kasittelija" => $kasittelija,		
    'token' => $token,
    'kayt_id' => $user_id
);

$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try{
	
	$respObj = $logic->ota_hakemus_viranomaiskasittelyyn($syoteparametrit);
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


