<?php
/**
 *
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_poista_hakija_tutkimusryhmasta.php
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

$hakemusversio_id = (isset($params["hakemusversio_id"]) ? $params["hakemusversio_id"] : TEST_HAKEMUSVERSIO_ID); 
$poistettavan_kayttaja_id = (isset($params["poistettavan_kayttaja_id"]) ? $params["poistettavan_kayttaja_id"] : NEW_USER_ID); 

$syoteparametrit = array(
    'pohja_rooli' => 'rooli_hakija',
    'hakemusversio_id' => $hakemusversio_id,
	'poistettavan_kayttaja_id' => $poistettavan_kayttaja_id,
    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");
$respObj = $logic->poista_hakija_tutkimusryhmasta($syoteparametrit);
if ($format == 'json') jsonOut($respObj);

print_r($respObj);

if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";



