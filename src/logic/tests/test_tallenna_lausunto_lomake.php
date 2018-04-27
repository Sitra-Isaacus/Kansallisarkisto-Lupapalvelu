<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_tallenna_lausunto_lomake.php
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
$hakemus_id = (isset($params["hakemus_id"]) ? $params["hakemus_id"] : null);
$kayttajan_rooli = (isset($params["kayttajan_rooli"]) ? $params["kayttajan_rooli"] : "rooli_lausunnonantaja"); 

$tallennuskohde_id = (isset($params["tallennuskohde_id"]) ? $params["tallennuskohde_id"] : null); 
$tallennuskohde = (isset($params["tallennuskohde"]) ? $params["tallennuskohde"] : "osio"); 
$tallennuskohde_nimi = (isset($params["tallennuskohde_nimi"]) ? $params["tallennuskohde_nimi"] : ""); 
$tallennuskohde_kentta = (isset($params["tallennuskohde_kentta"]) ? $params["tallennuskohde_kentta"] : ""); 
$tallennuskohde_arvo = (isset($params["tallennuskohde_arvo"]) ? $params["tallennuskohde_arvo"] : generateRandomString());

$syoteparametrit = array(
    "kayttajan_rooli" => $kayttajan_rooli,
    "lausunto_id" => $lausunto_id,
    "hakemus_id" => $hakemus_id,
	
    "data" => array(
        "tallennuskohde" => $tallennuskohde,
        "tallennuskohde_id" => $tallennuskohde_id,
        "tallennuskohde_nimi" => $tallennuskohde_nimi,
        "tallennuskohde_arvo" => $tallennuskohde_arvo,
        "tallennuskohde_kentta" => $tallennuskohde_kentta,
    ),

    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try {
	$respObj = $logic->tallenna_lausunto_lomake($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	//print_r($respObj);
	$res = std2ArrayRecursive($respObj);

	$Lausunto_tallennettu = searchArrayValueByKey($res, "Lausunto_tallennettu");

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	
}	
	
if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";
