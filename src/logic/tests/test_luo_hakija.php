<?php
/**
 * this test send the invite to user (user can be registered or not)
 *
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic
    php tests/test_luo_hakija.php
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
$new_hakija_email = (isset($params["sahkopostiosoite"]) ? $params["sahkopostiosoite"] : NEW_USER_EMAIL); 

$syoteparametrit = array(
    'data' => Array(
        'etunimi'    => 'Testi',
        'sukunimi'   => 'Hakija',
        'sahkoposti' => $new_hakija_email,
        'organisaatio'=> 'Kansallisarkisto',
        'oppiarvo'   => 'Test',
        'osoite'     => 'Test',
        'maa'        => 'Suomi',
        'puhelin'    => '0123456789',
        'roolit'     => Array(
            0 => 'rooli_tutkija'
        ),
    ),
    'pohja_rooli' => 'rooli_hakija',
    'hakemusversio_id' => $hakemusversio_id,
    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");
$respObj = $logic->luo_hakija_ja_laheta_sahkopostikutsu($syoteparametrit);
if ($format == 'json') jsonOut($respObj);

print_r($respObj);

if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";



