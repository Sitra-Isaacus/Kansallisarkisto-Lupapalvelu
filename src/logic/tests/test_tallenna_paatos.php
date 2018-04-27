<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_tallenna_paatos.php
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

$hakemus_id = (isset($params["hakemus_id"]) ? $params["hakemus_id"] : 469);
$kayttajan_rooli = (isset($params["kayttajan_rooli"]) ? $params["kayttajan_rooli"] : "rooli_kasitteleva"); 
$laheta_paatos_hyvaksyttavaksi = (isset($params["laheta_paatos_hyvaksyttavaksi"]) ? $params["laheta_paatos_hyvaksyttavaksi"] : false); 
$allekirjoita_paatos = (isset($params["allekirjoita_paatos"]) ? $params["allekirjoita_paatos"] : false); 
$palauta_paatos_kasiteltavaksi = (isset($params["palauta_paatos_kasiteltavaksi"]) ? $params["palauta_paatos_kasiteltavaksi"] : false); 

if($laheta_paatos_hyvaksyttavaksi){

	$syoteparametrit = array(
		"kayttajan_rooli" => $kayttajan_rooli,
		"hakemus_id" => $hakemus_id,
		
		"data" => array(
			"laheta_paatos_hyvaksyttavaksi" => $laheta_paatos_hyvaksyttavaksi
		),

		'token' => $token,
		'kayt_id' => $user_id,
	);

}

if($allekirjoita_paatos){

	$syoteparametrit = array(
		"kayttajan_rooli" => $kayttajan_rooli,
		"hakemus_id" => $hakemus_id,
		
		"data" => array(
			"allekirjoita_paatos" => $allekirjoita_paatos
		),

		'token' => $token,
		'kayt_id' => $user_id,
	);
	
}

$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try {
	
	$respObj = $logic->tallenna_paatos($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	//print_r($respObj);
	//$res = std2ArrayRecursive($respObj);

	//$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");
	//print_r($res);

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	
}	
	
if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";
