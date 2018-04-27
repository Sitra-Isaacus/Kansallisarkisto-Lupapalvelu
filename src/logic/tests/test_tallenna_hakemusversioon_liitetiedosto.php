<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_tallenna_hakemusversioon_liitetiedosto.php
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

if(!$hakemusversio_id) $hakemusversio_id = TEST_HAKEMUSVERSIO_ID;
$liitteen_koodi = (isset($params["liitteen_koodi"]) ? $params["liitteen_koodi"] : 1);  
$name = (isset($params["name"]) ? $params["name"] : "tutkimussuunnitelma.txt"); 

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
    "hakemusversio_id" => $hakemusversio_id,
    "tiedosto" => $tiedosto,
    "name" => $name,
    "liitteen_koodi" => $liitteen_koodi,	
	
    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try{
	
	$respObj = $logic->tallenna_hakemusversioon_liitetiedosto($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	print_r($respObj);

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	
}	
	
$cnt++;

echo $cnt . " tests done\n";



