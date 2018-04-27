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

handle_arguments($argv);
_echo("Auth test: ");

// Authorize as a test user. Test user credential can be found in the _test_config.php file
include("auth.inc.php"); // This gives us $token of the test user.

if ($format=="json") jsonOut($respObj);

if ($token) echo "OK\n";

echo $cnt . " tests done\n";



