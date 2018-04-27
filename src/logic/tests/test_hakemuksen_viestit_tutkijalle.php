<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_pdf_lausunto.php
 *
 * see tests/README.md for details
 */

define('FMAS', true);

include("_test_config.php");
include("func_lib.php");
include($dir."fmas_business_logic.php");

$logic = new fmas_business_logic();

handle_arguments($argv);
if (!isset($token)) include("auth.inc.php");

$syoteparametrit = array(
    "hakemus_id" => 323,

    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");
$respObj = $logic->hae_hakemuksen_viestit_tutkijalle($syoteparametrit);
if ($format == 'json') jsonOut($respObj);

print_r($respObj);

$cnt++;

echo $cnt . " tests done\n";



