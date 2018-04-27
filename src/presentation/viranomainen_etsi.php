<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Viranomaisen käyttöliittymän haku
 *
 * Created: 3.12.2015
 */

include_once '_fmas_ui.php';  

if(kayttaja_on_kirjautunut()){

	try {
		if ($api = createSoapClient()) {
								
			// Etsitään hakemusta
			if (isset($_POST['etsi'])) {
				$_POST = poista_erikoismerkit($_POST);
				$etsityt = suorita_logiikkakerroksen_funktio($api, "etsi_hakemuksia", array("data"=>$_POST, "viranomaisroolin_koodi"=>$_SESSION['kayttaja_rooli'], "token"=>$_SESSION['kayttaja_token'], "kayt_id"=>$_SESSION['kayttaja_id']));												
			}
					
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}
		
	include './ui/views/viranomainen_etsi_view.php';

} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	
	
?>