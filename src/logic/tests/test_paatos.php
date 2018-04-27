<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_paatos.php
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

$hakemus_id = (isset($params["hakemus_id"]) ? $params["hakemus_id"] : 437);
$kayttajan_rooli = (isset($params["kayttajan_rooli"]) ? $params["kayttajan_rooli"] : "rooli_hakija"); 

$syoteparametrit = array(
    'sivu' => "paatos_oletus",
    'hakemus_id' => $hakemus_id,
    'kayttajan_rooli' => $kayttajan_rooli,
    'token' => $token,
    'kayt_id' => $user_id
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("Lausunto test: ");
$respObj = $logic->hae_paatos($syoteparametrit);
if ($format == 'json') jsonOut($respObj);

print_r($respObj);

if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";