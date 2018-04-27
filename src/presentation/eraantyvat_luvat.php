<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Erääntyvät luvat
 *
 * Created: 15.10.2015
 */

include_once '_fmas_ui.php';  

if(kayttaja_on_kirjautunut()){	
	try {
		if ($api = createSoapClient()) {		
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_eraantyvat_kayttoluvat", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));	
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	include './ui/views/eraantyvat_luvat_view.php';
	
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}		

?>