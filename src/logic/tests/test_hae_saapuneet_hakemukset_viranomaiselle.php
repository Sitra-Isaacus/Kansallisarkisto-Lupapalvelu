<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_hae_saapuneet_hakemukset_viranomaiselle.php
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

if (!isset($token)) include("auth.inc.php");

$syoteparametrit = array(
    "kayttajan_rooli" => $kayttajan_rooli,	
    'token' => $token,
    'kayt_id' => $user_id,
);

$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try{
	
	$respObj = $logic->hae_saapuneet_hakemukset_viranomaiselle($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	$res = std2ArrayRecursive($respObj);
	
	//print_r($res[1]["HakemuksetDTO"]["Uudet"][0]["HakemusversioDTO"]->ID);
	//print_r($res[1]["HakemuksetDTO"]["Uudet"][0]["HakemusversioDTO"]->TutkimusDTO->ID);
	
	//print_r($respObj[1]->HakemuksetDTO["Uudet"][0]->HakemusversioDTO->ID);

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	print_r($respObj);
	
}	
	
$cnt++;

echo $cnt . " tests done\n";


