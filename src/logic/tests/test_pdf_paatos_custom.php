<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_pdf_paatos.php
 *
 * see tests/README.md for details
 */

define('FMAS', true);

include("_test_config.php");
include("func_lib.php");
include($dir."fmas_business_logic.php");

$logic = new fmas_business_logic();

// Authorize as a test user. Test user credential can be found in the _test_config.php file
handle_arguments($argv);
if (!isset($token)) include("auth.inc.php"); // This gives us $token of the test user.

define('TITLES_OUT', 1);

$syoteparametrit = array(
    'format' => 'pdf',
    'hakemus_id' => 145,
    'tutkimus_id' => 731,
    'kayttajan_rooli' => 'rooli_hakija',
    'token' => $token,
    'kayt_id' => $user_id
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("Paatos PDF test: ");

$res = $logic->hae_paatos($syoteparametrit);

echo "titles:\n";

print_r($res);



