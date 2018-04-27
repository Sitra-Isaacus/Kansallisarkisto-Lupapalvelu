<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_tallenna_hakemus.php
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

if (!$hakemusversio_id) $hakemusversio_id = TEST_HAKEMUSVERSIO_ID;

$tallennuskohde_id = (isset($params["tallennuskohde_id"]) ? $params["tallennuskohde_id"] : 2997); // Oletus osio id = tutk. nimi engl.
$tallennuskohde = (isset($params["tallennuskohde"]) ? $params["tallennuskohde"] : "osio"); 
$tallennuskohde_nimi = (isset($params["tallennuskohde_nimi"]) ? $params["tallennuskohde_nimi"] : ""); 
$tallennuskohde_kentta = (isset($params["tallennuskohde_kentta"]) ? $params["tallennuskohde_kentta"] : ""); 
$tallennuskohde_arvo = (isset($params["tallennuskohde_arvo"]) ? $params["tallennuskohde_arvo"] : generateRandomString());
$tallennettavat_tiedot = (isset($params["tallennettavat_tiedot"]) ? $params["tallennettavat_tiedot"] : "hakemus_perustiedot"); 
$haetun_aineiston_indeksi = (isset($params["haetun_aineiston_indeksi"]) ? $params["haetun_aineiston_indeksi"] : 0); 

$syoteparametrit = array(
    "sivu" => $tallennettavat_tiedot,
    "kayttajan_rooli" => "rooli_hakija",
    //"tutkimus_id" => $tutkimus_id,
    "hakemusversio_id" => $hakemusversio_id,
    "haetun_aineiston_indeksi" => $haetun_aineiston_indeksi,
	
    "data" => array(
        "tallennuskohde" => $tallennuskohde,
        "tallennuskohde_id" => $tallennuskohde_id,
        "tallennuskohde_nimi" => $tallennuskohde_nimi,
        "tallennuskohde_arvo" => $tallennuskohde_arvo,
        "tallennuskohde_kentta" => $tallennuskohde_kentta,
    ),

    "tallennettavat_tiedot" => $tallennettavat_tiedot,

    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try {
	$respObj = $logic->tallenna_hakemus($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	//print_r($respObj);
	$res = std2ArrayRecursive($respObj);

	$Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	//print_r($ex->faultcode);
	
}	
	
if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";
