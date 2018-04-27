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

$lausunto_id = (isset($params["lausunto_id"]) ? $params["lausunto_id"] : null);
$hakemus_id = (isset($params["hakemus_id"]) ? $params["hakemus_id"] : null);

$syoteparametrit = array(
    'format' => 'pdf',
    'lausunto_id' => $lausunto_id,
    'hakemus_id' => $hakemus_id,

    'token' => $token,
    'kayt_id' => $user_id
);

$syoteparametrit = toObjArray($syoteparametrit); // translate from human format to the format wsdl uses

_echo("Lausunto PDF test: ");

try {

	$respObj = $logic->hae_lausunto($syoteparametrit);
	if ($format == 'json') jsonOut($respObj);

	$pdf_content = findValRecursive('pdf_content', $respObj);
	if (!$pdf_content) {
		debug_log($respObj);
		echo "PDF generation error. hae_lausunto output is dumped to /tmp/debug_test.log\n";
	} elseif(strlen($pdf_content) < 5000 ) {
		debug_log($respObj);
		echo "PDF generation error. pdf_content is less than 10k, probably empty document\n";
	} else {
		file_put_contents("/tmp/test_lausunto.pdf", base64_decode($pdf_content));
		echo "OK\n";
		echo "PDF file saved to /tmp/test_lausunto.pdf - please remove after testing\n\n";
	}

} catch (SoapFault $ex) {
	
	$respObj = array();
	$respObj[0] = $ex->faultcode;
	if ($format == 'json') jsonOut($respObj);
	
}	
	
if (!isset($cnt)) $cnt = 0;
$cnt++;

echo $cnt . " tests done\n";