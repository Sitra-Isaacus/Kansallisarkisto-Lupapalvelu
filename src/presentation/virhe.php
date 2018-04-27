<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Error handling
 *
 * Created: 9.9.2016
 */

include_once '_fmas_ui.php'; 

$_GET = poista_erikoismerkit($_GET);
$soap_virheilmoitus = "";

if(isset($_GET["virhe"])) $soap_virheilmoitus = $_GET["virhe"];
if(isset($_SESSION["soap_virheilmoitus"])) $soap_virheilmoitus = $_SESSION["soap_virheilmoitus"];
		
include './ui/template/header.php';	

?>

<div class="laatikko10">
	<h1><?php echo VIRHE; ?></h1>
	<p>
	<?php if(isset($soap_virheilmoitus)){ ?>
		<?php echo $soap_virheilmoitus; ?><br>
	<?php } else { ?>
		<?php echo YLEINEN_VIRHEILMOITUS; ?><br>
	<?php } ?>
	</p>			
</div>

<?php
	include './ui/template/footer.php';
?>