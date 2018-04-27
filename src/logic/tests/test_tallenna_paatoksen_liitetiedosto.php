<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_tallenna_paatoksen_liitetiedosto.php
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

$paatos_id = (isset($params["paatos_id"]) ? $params["paatos_id"] : null); 
$name = (isset($params["name"]) ? $params["name"] : "tutkimussuunnitelma.txt"); 
$liitteen_nimi = (isset($params["liitteen_nimi"]) ? $params["liitteen_nimi"] : "Päätös"); 

if(isset($params["tiedosto"])){
	$tiedosto = $params["tiedosto"];
} else {

	$file = 'tests/tutkimussuunnitelma.txt';
	$data = 'Unit testing';
	file_put_contents($file, $data);

	$tiedosto = file_get_contents($file);
	$tiedosto = base64_encode($tiedosto);
	
}

$syoteparametrit = array(
    "paatos_id" => $paatos_id,
    "tiedosto" => $tiedosto,
    "name" => $name,	
    "liitteen_nimi" => $liitteen_nimi,		
	
    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try{
	
	$respObj = $logic->tallenna_paatoksen_liitetiedosto($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	print_r($respObj);

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	
}	
	
$cnt++;

echo $cnt . " tests done\n";