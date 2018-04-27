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

$params = handle_arguments($argv);

if (!isset($token)) include("auth.inc.php");
if (!isset($hakemusversio_id)) $hakemusversio_id = TEST_HAKEMUSVERSIO_ID; 
if (!isset($tutkimus_id)) $tutkimus_id = TEST_TUTKIMUS_ID;

$syoteparametrit = array(
    "tutkimus_id" => $tutkimus_id,
    "hakemusversio_id" => $hakemusversio_id,
    "sivu" => "hakemus_perustiedot",
    "generoi_pdf" => 1,

    "kayttajan_rooli" => "rooli_hakija",

    'token' => $token,
    'kayt_id' => $user_id,
);
$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("test: ");

try{

	$respObj = $logic->hae_hakemusversio($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	//print_r($respObj); // huge!

	$pdf_content = findValRecursive('pdf_content', $respObj);
	if (!$pdf_content) {
		debug_log($respObj);
		echo "PDF generation error. hae_hakemusversio output is dumped to /tmp/debug_test.log\n";
	} elseif (strlen($pdf_content)<7000) {
		debug_log($respObj);
		echo "PDF generation error. pdf_content is less than 7k, probably empty document\n";
	} else {
		file_put_contents("/tmp/test_hakemus.pdf", base64_decode($pdf_content));
		echo "OK\n";
		echo "PDF file saved to /tmp/test_hakemus.pdf - please remove after testing\n\n";
	}

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	
}
	
if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";



