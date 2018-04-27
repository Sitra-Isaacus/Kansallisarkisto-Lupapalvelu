<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_talenna_hakemus.php
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
    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");
$respObj = $logic->hae_hakemukset_tutkijalle($syoteparametrit);
if ($format == 'json') jsonOut($respObj);

//print_r($respObj);
$res = std2ArrayRecursive($respObj);

echo "count: ".count($res)."\n";
print_r($res[0]);

$cnt++;

echo $cnt . " tests done\n";



