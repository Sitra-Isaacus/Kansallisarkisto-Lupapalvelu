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

// Authorize as a test user. Test user credentials can be found in the _test_config.php file
handle_arguments($argv);
if (!isset($token)) include("auth.inc.php");


$syoteparametrit = array(
    'format' => 'pdf',
    'generoi_pdf'=>true,
    'hakemus_id' => 151,
    'tutkimus_id' => 731,
    'kayttajan_rooli' => 'rooli_hakija',
    'token' => $token,
    'kayt_id' => $user_id
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("Paatos PDF test: ");

$respObj = $logic->hae_paatos($syoteparametrit);
if ($format == 'json') jsonOut($respObj);

$pdf_content = findValRecursive('pdf_content', $respObj);
if (!$pdf_content) {
    debug_log($respObj);
    echo "PDF generation error. hae_paatos output is dumped to /tmp/debug_test.log\n";
} elseif (strlen($pdf_content)<10000) {
    debug_log($respObj);
    echo "PDF generation error. pdf_content is less than 10k, probably empty document\n";
} else {
    file_put_contents("/tmp/test_paatos.pdf", base64_decode($pdf_content));
    echo "OK\n";
    echo "PDF file saved to /tmp/test_paatos.pdf - please remove after testing\n\n";
}

if (!isset($cnt)) $cnt = 0;
$cnt++;

_echo($cnt . " tests done\n");



