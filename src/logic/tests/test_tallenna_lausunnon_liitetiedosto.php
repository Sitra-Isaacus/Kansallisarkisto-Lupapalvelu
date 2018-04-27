<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_tallenna_lausunnon_liitetiedosto.php
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

$lausunto_id = (isset($params["lausunto_id"]) ? $params["lausunto_id"] : null); 
$name = (isset($params["name"]) ? $params["name"] : "lausunto.txt"); 

if(isset($params["tiedosto"])){
	$tiedosto = $params["tiedosto"];
} else {

	$file = 'tests/lausunto.txt';
	$data = 'Unit testing';
	file_put_contents($file, $data);

	$tiedosto = file_get_contents($file);
	$tiedosto = base64_encode($tiedosto);
	
}

$syoteparametrit = array(
    "lausunto_id" => $lausunto_id,
    "tiedosto" => $tiedosto,
    "name" => $name,	
	
    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try{
	
	$respObj = $logic->tallenna_lausunnon_liitetiedosto($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	print_r($respObj);

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	
}	
	
$cnt++;

echo $cnt . " tests done\n";